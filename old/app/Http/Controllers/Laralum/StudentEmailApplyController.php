<?php

namespace App\Http\Controllers\Laralum;

use App\AdmissionSession;
use App\Department;
use App\Exports\StudentEmailExport;
use App\Faculty;
use App\Hall;
use App\Http\Controllers\Controller;
use App\Http\Requests\Laralum\AdmissionSessionRequest;
use App\Http\Requests\Laralum\ProgramRequest;
use App\Http\Requests\Laralum\StudentEmailApplyRequest;
use App\Notifications\StudentEmailApplied;
use App\Notifications\StudentEmailCreated;
use App\Notifications\StudentEmailRejected;
use App\Program;
use App\Student;
use App\StudentEmailApply;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Storage;

class StudentEmailApplyController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index()
    {
        $this->authorize('STUDENT-EMAIL-VIEW');
        $data['emails'] = StudentEmailApply::allWithOptionalFilter(['admissionSession', 'program', 'department', 'hall', 'globalStatus']);
        $data['sessions'] = AdmissionSession::all();
        $data['departments'] = Department::all();
        $data['programs'] = Program::all();
        $data['halls'] = Hall::all();
        $data['statuses'] = StudentEmailApply::allStatus();
        return view('laralum.student-email-apply.index', $data);
    }

    /**
     * FrontEnd View For Studuent Domain Email Apply
     *
     * @return void
     */
    public function apply()
    {
        $data['faculties'] = Faculty::all();
        $data['halls'] = Hall::all();
        $data['sessions'] = AdmissionSession::all(['id', 'name', 'is_verifyable']);
        $data['programs'] = Program::all(['id', 'name']);
        return view('frontend.domain-email-apply', $data);
    }

    /**
     * Through validation exception
     *
     * @param StudentEmailApplyRequest $request
     * @return void
     */
    public function validateRegistrationNumber(StudentEmailApplyRequest $request, $ignore = false)
    {
        $message = ['registration_number' => 'This Registration Number Has Been Taken'];
        $query = StudentEmailApply::whereRegistrationNumber($request->registration_number)
            ->where('global_status_id', '!=', StudentEmailApply::onlyStatus(StudentEmailApply::$rejected)->id);

        !$ignore ?: $query->where('id', '!=', $ignore);

        if (in_array($request->program_id, [1, 3]) && $query->whereIn('program_id', [1, 3])->exists()) {
            throw ValidationException::withMessages($message);
        } else {
            if ($query->whereProgramId($request->program_id)->exists()) {
                throw ValidationException::withMessages($message);
            }
        }
    }

    /**
     * Validate during email apply
     *
     * @param StudentEmailApplyRequest $request
     * @return void
     */
    public function validateRegAndStudent(StudentEmailApplyRequest $request)
    {
        $this->validateRegistrationNumber($request);
        return $this->validateStudentExistance($request);
    }

    /**
     * After submit apply form
     *
     * @param StudentEmailApplyRequest $request
     * @param StudentEmailApply $studentEmailApply
     * @return void
     */
    public function store(StudentEmailApplyRequest $request, StudentEmailApply $studentEmailApply)
    {
        $validated = $request->validated();

        // check duplicate registration number
        $this->validateRegistrationNumber($request);
        $studentEmailApply->fill($validated);

        // check if the student info exists on system
        if ($request->registration_number == $request->render_reg_num && $this->validateStudentExistance($request)) {
            $studentEmailApply->markChecked();
        }

        $studentEmailApply->save();
        $imageName = $studentEmailApply->username . '.' . $request->id_card->extension();
        $request->id_card->storeAs('public/image/student-email-apply', $imageName);
        $studentEmailApply->update(['image' => $imageName]);
        try {
            $studentEmailApply->notify(new StudentEmailApplied);
        } catch (\Exception $e) {
        }
        return redirect()->route('Frontend::student::email::apply')->with('success', 'You Have Successfully Applied. We Will Email You Credential on Existing Email Very Soon');
    }

    /**
     * Admission Session List
     *
     * @return void
     */
    public function admissionSessionList()
    {
        $this->authorize('ADMIN');
        return view('laralum.student-email-apply.admission-session.index', ['sessions' => AdmissionSession::all()]);
    }

    /**
     * Admission Session Save
     *
     * @param AdmissionSessionRequest $request
     * @return void
     */
    public function admissionSessionStore(AdmissionSessionRequest $request)
    {
        AdmissionSession::create($request->validated());
        return redirect()->route('Laralum::student-email-apply::admission-session::list')->with('success', 'Admission Session Created Successfully');
    }

    /**
     * Admission Session Edit Page
     *
     * @param AdmissionSession $admissionSession
     * @return void
     */
    public function admissionSessionEdit(AdmissionSession $admissionSession)
    {
        $this->authorize('ADMIN');
        return view('laralum.student-email-apply.admission-session.edit', ['session' => $admissionSession]);
    }

    /**
     * Admission Session Update Method
     *
     * @param AdmissionSessionRequest $request
     * @param AdmissionSession $admissionSession
     * @return void
     */
    public function admissionSessionUpdate(AdmissionSessionRequest $request, AdmissionSession $admissionSession)
    {
        $admissionSession->update($request->validated());
        return redirect()->route('Laralum::student-email-apply::admission-session::list')->with('success', 'Admission Session Updated Successfully');
    }

    /**
     * Admission Session Delete Method and Redirect List
     *
     * @param AdmissionSession $admissionSession
     * @return void
     */
    public function admissionSessionDelete(AdmissionSession $admissionSession)
    {
        $this->authorize('ADMIN');
        $admissionSession->delete();
        return redirect()->route('Laralum::student-email-apply::admission-session::list')->with('success', 'Admission Session Deleted Successfully');
    }

    /**
     * Program List
     *
     * @return void
     */
    public function programList()
    {
        $this->authorize('ADMIN');
        return view('laralum.student-email-apply.program.index', ['programs' => Program::all()]);
    }

    /**
     * Program Save
     *
     * @param ProgramRequest $request
     * @return void
     */
    public function programStore(ProgramRequest $request)
    {
        Program::create($request->validated());
        return redirect()->route('Laralum::student-email-apply::program::list')->with('success', 'Program Created Successfully');
    }

    /**
     * Program Edit Page
     *
     * @param Program $program
     * @return void
     */
    public function programEdit(Program $program)
    {
        $this->authorize('ADMIN');
        return view('laralum.student-email-apply.program.edit', ['program' => $program]);
    }

    /**
     * Program Update Method
     *
     * @param ProgramRequest $request
     * @param Program $program
     * @return void
     */
    public function ProgramUpdate(ProgramRequest $request, Program $program)
    {
        $program->update($request->validated());
        return redirect()->route('Laralum::student-email-apply::program::list')->with('success', 'Program Updated Successfully');
    }

    /**
     * Program Delete Method and Redirect
     *
     * @param Program $program
     * @return void
     */
    public function ProgramDelete(Program $program)
    {
        $this->authorize('ADMIN');
        $program->delete();
        return redirect()->route('Laralum::student-email-apply::program::list')->with('success', 'Program Deleted Successfully');
    }

    /**
     * Status Update as Complete
     *
     * @param StudentEmailApply $studentEmailApply
     * @return void
     */
    public function markCompleted(StudentEmailApply $email)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $email->notify(new StudentEmailCreated);
        $email->markCompleted();
        $email->save();
        return back()->with('success', 'A Mail Has Been Sent to Student with Credential');
        // return redirect()->route('Laralum::student-email-apply::list')->with('success', 'A Mail Has Been Sent to Student with Credential');
    }

    /**
     * Status Update as Pending
     *
     * @param StudentEmailApply $studentEmailApply
     * @return void
     */
    public function markPending(StudentEmailApply $email)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $email->global_status_id = StudentEmailApply::onlyStatus(StudentEmailApply::$pending)->id;
        $email->save();
        return redirect()->route('Laralum::student-email-apply::list')->with('success', 'Status Updated to Pending');
    }

    /**
     * Status Update as Rejected
     *
     * @param StudentEmailApply $studentEmailApply
     * @return void
     */
    public function markRejected(StudentEmailApply $email, Request $request)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $this->validate($request, ['comment' => 'required'], ['comment.required' => 'To Reject Email Apply, Reason is Required']);
        $email->notify(new StudentEmailRejected($request->comment));
        $email->comment = $request->comment;
        $email->markrejected();
        $email->save();
        return back()->with('success', 'A Mail Has Been Sent to Student');
        // return redirect()->route('Laralum::student-email-apply::list')->with('success', 'Email Apply Rjected');
    }

    /**
     * Status Update as Rejected
     *
     * @param StudentEmailApply $studentEmailApply
     * @return void
     */
    public function markChecked(StudentEmailApply $email)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $email->markChecked();
        $email->save();
        return back()->with('success', 'Status Updated to Checked');
        // return redirect()->route('Laralum::student-email-apply::list')->with('success', 'Status Updated to Checked');
    }

    /**
     * Apply Edit
     *
     * @param StudentEmailApply $email
     * @return void
     */
    public function edit(StudentEmailApply $email)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $data['faculties'] = Faculty::all();
        $data['halls'] = Hall::all();
        $data['sessions'] = AdmissionSession::all(['id', 'name']);
        $data['programs'] = Program::all(['id', 'name']);
        $data['statuses'] = StudentEmailApply::allStatus();
        $data['email'] = $email;
        return view('laralum.student-email-apply.edit', $data);
    }

    /**
     * Update Student Email Apply
     *
     * @param StudentEmailApply $email
     * @param StudentEmailApplyRequest $request
     * @return void
     */
    public function update(StudentEmailApply $email, StudentEmailApplyRequest $request)
    {
        $this->authorize('STUDENT-EMAIL-MANAGE');
        $validated = $request->validated();
        $this->validateRegistrationNumber($request, $email->id);
        $email->updated_by = auth()->id();
        $email->update($validated);

        if ($request->hasFile('id_card')) {
            (!$email->image) ?: Storage::delete("public/image/student-email-apply/$email->image");

            $imageName = $email->username . '.' . $request->id_card->extension();
            $request->id_card->storeAs('public/image/student-email-apply', $imageName);
            $email->update(['image' => $imageName]);
        }

        return redirect()->route('Laralum::student-email-apply::list')->with('success', 'Student Email Updated Successfully');
    }

    /**
     * Email Apply Delete
     *
     * @param StudentEmailApply $email
     * @return void
     */
    public function destroy(StudentEmailApply $email)
    {
        $this->authorize('ADMIN');
        $email->delete();
        return redirect()->route('Laralum::student-email-apply::list')->with('success', 'Student Email Deleted Successfully');
    }

    /**
     * Email Apply View Page
     *
     * @param StudentEmailApply $email
     * @return void
     */
    public function detail(StudentEmailApply $email)
    {
        $this->authorize('STUDENT-EMAIL-VIEW');
        return view('laralum.student-email-apply.detail', compact('email'));
    }

    /**
     * Export email list to csv
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request)
    {
        if (!empty($request->columns)) {
            return (new StudentEmailExport())->onlyColumns($request->columns);
        } else {
            return redirect()->route('Laralum::student-email-apply::list')->with('error', 'No Field Selected for Export');
        }
    }

    /**
     * Generate email user name
     *
     * @param Request $request
     * @return void
     */
    public function generateUserName(Request $request)
    {
        return Helper::userNameGenerator($request->name, Program::find($request->program_id)->slug, AdmissionSession::find($request->admission_session_id)->name);
    }

    /**
     * Find invalid email
     *
     * @return void
     */
    public function findWrongEmail()
    {
        /* $emails = StudentEmailApply::cursor()->filter(function ($email) {
            return \Illuminate\Support\Str::startsWith($email->contact_phone, '+88') || \Illuminate\Support\Str::startsWith($email->contact_phone, '88') || \Illuminate\Support\Str::startsWith($email->contact_phone, '088');
        });

        foreach ($emails as $email) {
            StudentEmailApply::whereId($email->id)->update(['contact_phone' => (string) \Illuminate\Support\Str::replaceFirst('88', '', $email->contact_phone)]);
            echo $email->contact_phone . ' ' . $email->id . '</br>';
        } */
    }

    /**
     * Search registration number availability with student database
     *
     * @param Request $request
     * @return void
     */
    public function searchRegistrationExistance(Request $request)
    {
        $student = Student::with('department:id,faculty_id')->whereRegistration($request->registration_number)->get();
        if ($student->isNotEmpty()) {
            return [
                'data' => $student->first(),
                'count' => $student->count(),
            ];
        }

        return false;
    }

    /**
     * Validate student existance
     *
     * @param [type] $registration
     * @param [type] $sessoinID
     * @param [type] $sessionName
     * @return object
     */
    public function validateWithStudentExsistance($registration, $sessoinID, $sessionName)
    {
        $student = Student::with('department:id,faculty_id')
                ->whereAdmissionSessionId($sessoinID)
                ->whereRegistration($registration)
                ->first();

        if (!$student) {
            throw ValidationException::withMessages(['registration_number' => "For $sessionName Session, Registration Number Doesn't Found on Our Records"]);
        }

        return $student;
    }

    /**
     * Check student existance and validate fdasfa
     *
     * @param StudentEmailApplyRequest $request
     * @return void
     */
    public function validateStudentExistance(StudentEmailApplyRequest $request)
    {
        return Student::whereRegistration($request->registration_number)
                ->whereName(implode(' ', array_filter([
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name
                ])))
                ->exists();
    }

    /**
     * Check student existance and validate
     *
     * @param StudentEmailApplyRequest $request
     * @return void
     */
    public function validateStudentExistanceOld(StudentEmailApplyRequest $request)
    {
        $session = AdmissionSession::find($request->admission_session_id);

        if ($session->is_verifyable) {
            if ($this->validateWithStudentExsistance(
                $request->registration_number,
                $request->admission_session_id,
                $session->name
            )->name
                !=
                implode(' ', array_filter([
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name
                ]))) {
                throw ValidationException::withMessages(['registration_number' => "For $session->name Session, Registration Number $request->registration_number, Doesn't Match With Student Name on Our Record"]);
            }

            return 'checked';
        }
    }
}
