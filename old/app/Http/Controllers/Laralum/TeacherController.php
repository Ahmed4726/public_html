<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Exports\TeachersExport;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Teacher;
use App\TeacherActivity;
use App\TeacherDesignation;
use App\TeacherEducation;
use App\TeacherTeaching;
use App\TeacherExperience;
use App\TeacherPublication;
use App\TeacherPublicationType;
use App\TeacherStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Laralum;
use Storage;

class TeacherController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('TEACHER');
        $data['faculties'] = Faculty::all();
        // $data['departments'] = Department::all();
        $data['designations'] = TeacherDesignation::all();
        $data['statuses'] = TeacherStatus::all();
        $data['teachers'] = Teacher::allWithOptionalFilter($request->search, $request->status, $request->department_id, $request->designation_id, false, ['department', 'designationInfo', 'statusInfo'])->appends($request->all());
        $data['sortable'] = false;
        if (count(array_filter($request->except(['page', 'faculty_id']))) == 1 && key(array_filter($request->except(['page', 'faculty_id']))) == 'department_id') {
            $data['sortable'] = true;
        }

        return view('laralum.teacher.index', $data);
    }

    /**
     * Teacher export by excel
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request)
    {
        if (!empty($request->headings)) {
            $relations = [];
            $export = new TeachersExport();
            $export->search($request->search)
                ->status($request->status)
                ->department($request->department_id)
                ->designation($request->designation_id)
                ->withHeadings($request->headings);

            !in_array('Department', $request->headings) ?: array_push($relations, 'department');
            !in_array('Designation', $request->headings) ?: array_push($relations, 'designationInfo');
            !in_array('Status', $request->headings) ?: array_push($relations, 'statusInfo');

            return $export->withRelations($relations);
        } else {
            return redirect()->back()->with('error', 'Please Select at Least a Field to Export');
        }
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentTeacher($department, Request $request)
    {
        $data['uriValue'] = $department;
        if (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['teachers'] = Teacher::allWithOptionalFilter($request->search, $request->status, $department, $request->designation_id, false, ['department', 'designationInfo', 'statusInfo'])->appends($request->all());
            $data['uri'] = 'department';
        }
        $data['sortable'] = false;
        $data['designations'] = TeacherDesignation::all();
        $data['statuses'] = TeacherStatus::all();
        $data['departments'] = Department::all();

        if (count(array_filter($request->except('page'))) == 0) {
            $data['sortable'] = true;
        }

        return view('laralum.teacher.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['statuses'] = TeacherStatus::all();
        $data['designations'] = TeacherDesignation::all();
        return view('laralum.teacher.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'department_id' => 'required',
            'status' => 'required',
            'designation_id' => 'required',
            'slug' => 'required|unique:teachers,slug',
        ]);

        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->additional_emails = $request->additional_emails;
        $teacher->department_id = $request->department_id;
        $teacher->status = $request->status;
        $teacher->designation_id = $request->designation_id;
        $teacher->slug = $request->slug;
        $teacher->research_interest = $request->research_interest;
        $teacher->biography = $request->biography;
        $teacher->google_scholar = $request->google_scholar;
        $teacher->research_gate = $request->research_gate;
        $teacher->orcid = $request->orcid;
        $teacher->facebook = $request->facebook;
        $teacher->twitter = $request->twitter;
        $teacher->linkedin = $request->linkedin;
        $teacher->cell_phone = $request->cell_phone;
        $teacher->work_phone = $request->work_phone;
        if ($request->sorting_order) {
            $teacher->sorting_order = $request->sorting_order;
        }

        if ($request->hasFile('image')) {
            $maxSize = 500;
            $height = 200;
            $width = 200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/teacher/$fileName", $width, $height);
            $teacher->image_url = $fileName;
        }

        $teacher->save();
        $user = new UsersController();
        $user->createUerForTeacher($teacher);
        return redirect()->route('Laralum::teacher::list', ['department_id' => $request->department_id])->with('success', 'Teacher Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['faculties'] = Faculty::all();
        $data['teacher'] = $teacher;
        $data['departments'] = Department::all();
        $data['statuses'] = TeacherStatus::all();
        $data['designations'] = TeacherDesignation::all();
        return view('laralum.teacher.edit', $data);
    }

    public function update(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);

        $this->validate($request, [
            'name' => 'required',
            'designation_id' => 'required',
        ]);

        if (Auth::user()->can('ADMIN')) {
            $this->validate($request, [
                'email' => 'required|email',
                'department_id' => 'required',
                'status' => 'required',
                'slug' => [
                    'required',
                    Rule::unique('teachers')->ignore($teacher->id),
                ]
            ]);
        }

        $teacher->name = $request->name;
        $teacher->designation_id = $request->designation_id;
        $teacher->additional_emails = $request->additional_emails;
        $teacher->research_interest = $request->research_interest;
        $teacher->biography = $request->biography;
        $teacher->google_scholar = $request->google_scholar;
        $teacher->research_gate = $request->research_gate;
        $teacher->orcid = $request->orcid;
        $teacher->facebook = $request->facebook;
        $teacher->twitter = $request->twitter;
        $teacher->linkedin = $request->linkedin;
        $teacher->cell_phone = $request->cell_phone;
        $teacher->work_phone = $request->work_phone;
        if (isset($request->sorting_order) && $request->sorting_order) {
            $teacher->sorting_order = $request->sorting_order;
        }

        if (Auth::user()->can('ADMIN')) {
            $teacher->status = $request->status;
            $teacher->slug = $request->slug;
            $teacher->email = $request->email;
            $teacher->department_id = $request->department_id;
        }

        ($teacher->image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = 500;
            $height = 200;
            $width = 200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/teacher/$fileName", $width, $height);
            if ($teacher->image_url) {
                Storage::delete("public/image/teacher/$teacher->image_url");
            }
            $teacher->image_url = $fileName;
        }

        $teacher->save();
        return redirect()->route('Laralum::teacher::list', ['department_id' => $request->department_id])->with('success', 'Teacher Updated Successfully');
    }

    public function delete(Teacher $teacher)
    {
        $this->authorize('ADMIN');
        $teacher->delete();
        return redirect()->route('Laralum::teacher::list', ['department_id' => $teacher->department_id])->with('success', 'Teacher Deleted Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function designation()
    {
        $this->authorize('ADMIN');
        $data['designations'] = TeacherDesignation::all();
        return view('laralum.teacher.designation', $data);
    }

    /**
     * @param TeacherDesignation $designation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function designationEdit(TeacherDesignation $designation)
    {
        $this->authorize('ADMIN');
        $data['designation'] = $designation;
        return view('laralum.teacher.designation-edit', $data);
    }

    /**
     * @param TeacherDesignation $designation
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function designationUpdate(TeacherDesignation $designation, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required'
        ]);

        $designation->name = $request->name;
        $designation->save();
        return redirect()->route('Laralum::teacher::designation::list')->with('success', 'Designation Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function designationStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required'
        ]);

        $designation = new TeacherDesignation();
        $designation->name = $request->name;
        $designation->save();
        return redirect()->route('Laralum::teacher::designation::list')->with('success', 'Designation Created Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function status()
    {
        $this->authorize('ADMIN');
        $data['statuses'] = TeacherStatus::all();
        return view('laralum.teacher.status', $data);
    }

    /**
     * @param TeacherStatus $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusEdit(TeacherStatus $status)
    {
        $this->authorize('ADMIN');
        $data['status'] = $status;
        return view('laralum.teacher.status-edit', $data);
    }

    /**
     * @param TeacherStatus $status
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusUpdate(TeacherStatus $status, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required'
        ]);

        $status->name = $request->name;
        $status->save();
        return redirect()->route('Laralum::teacher::status::list')->with('success', 'Status Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required'
        ]);

        $status = new TeacherStatus();
        $status->name = $request->name;
        $status->save();
        return redirect()->route('Laralum::teacher::status::list')->with('success', 'Status Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function educationList(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['educations'] = TeacherEducation::allWithOptionalFilter($request->search, $teacher->id, $request->status)->appends($request->all());
        $data['sortable'] = false;
        if (empty(array_filter($request->except('page')))) {
            $data['sortable'] = true;
        }
        return view('laralum.teacher.education.index', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function educationCreate(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        return view('laralum.teacher.education.create', $data);
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function educationStore(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'institute' => 'required'
        ]); */

        $education = new TeacherEducation();
        $education->institute = $request->institute;
//        $education->status = $request->status;
        $education->description = $request->description;
        $education->period = $request->period;
        $teacher->educations()->save($education);
        return redirect()->route('Laralum::teacher::education::list', ['teacher' => $teacher->id])->with('success', 'Teacher Education Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function educationDelete(Teacher $teacher, TeacherEducation $education)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $education->delete();
        return redirect()->route('Laralum::teacher::education::list', ['teacher' => $teacher->id])->with('success', 'Education deleted successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function educationEdit(Teacher $teacher, TeacherEducation $education)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['education'] = $education;
        return view('laralum.teacher.education.edit', $data);
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function educationUpdate(Teacher $teacher, TeacherEducation $education, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'institute' => 'required'
        ]); */

        $education->institute = $request->institute;
//        $education->status = $request->status;
        $education->description = $request->description;
        $education->period = $request->period;
        $teacher->educations()->save($education);
        return redirect()->route('Laralum::teacher::education::list', ['teacher' => $teacher->id])->with('success', 'Teacher Education Updated Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function teachingList(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['educations'] = TeacherTeaching::allWithOptionalFilter($request->search, $teacher->id, $request->status)->appends($request->all());
        $data['sortable'] = false;
        if (empty(array_filter($request->except('page')))) {
            $data['sortable'] = true;
        }
        return view('laralum.teacher.teaching.index', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function teachingCreate(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        return view('laralum.teacher.teaching.create', $data);
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function teachingStore(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);

        $education = new TeacherTeaching();
        $education->course_code = $request->course_code;
        $education->course_title = $request->course_title;
        $education->semester = $request->semester;
        $teacher->teachings()->save($education);
        return redirect()->route('Laralum::teacher::teaching::list', ['teacher' => $teacher->id])->with('success', 'Teacher Teaching Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function teachingDelete(Teacher $teacher, TeacherTeaching $teaching)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $teaching->delete();
        return redirect()->route('Laralum::teacher::teaching::list', ['teacher' => $teacher->id])->with('success', 'Teaching deleted successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function teachingEdit(Teacher $teacher, TeacherTeaching $teaching)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['education'] = $teaching;
        return view('laralum.teacher.teaching.edit', $data);
    }

    /**
     * @param Teacher $teacher
     * @param TeacherEducation $education
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function teachingUpdate(Teacher $teacher, TeacherTeaching $teaching, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $teaching->course_code = $request->course_code;
        $teaching->course_title = $request->course_title;
        $teaching->semester = $request->semester;
        $teacher->teachings()->save($teaching);
        return redirect()->route('Laralum::teacher::teaching::list', ['teacher' => $teacher->id])->with('success', 'Teacher Teaching Updated Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activityList(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['activities'] = TeacherActivity::allWithOptionalFilter($request->search, $teacher->id, $request->status)->appends($request->all());
        $data['sortable'] = false;
        if (empty(array_filter($request->except('page')))) {
            $data['sortable'] = true;
        }

        return view('laralum.teacher.activity.index', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activityCreate(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        return view('laralum.teacher.activity.create', $data);
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function activityStore(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'organization' => 'required',
        ]); */

        $activity = new TeacherActivity();
        $activity->organization = $request->organization;
        $activity->position = $request->position;
        $activity->description = $request->description;
        $activity->period = $request->period;
        $teacher->activities()->save($activity);
        return redirect()->route('Laralum::teacher::activity::list', ['teacher' => $teacher->id])->with('success', 'Teacher Activity Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherActivity $activity
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activityDelete(Teacher $teacher, TeacherActivity $activity)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $activity->delete();
        return redirect()->route('Laralum::teacher::activity::list', ['teacher' => $teacher->id])->with('success', 'Activity deleted successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherActivity $activity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activityEdit(Teacher $teacher, TeacherActivity $activity)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['activity'] = $activity;
        return view('laralum.teacher.activity.edit', $data);
    }

    /**
     * @param Teacher $teacher
     * @param TeacherActivity $activity
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function activityUpdate(Teacher $teacher, TeacherActivity $activity, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'organization' => 'required',
        ]); */

        $activity->organization = $request->organization;
        $activity->position = $request->position;
        $activity->description = $request->description;
        $activity->period = $request->period;
        $teacher->activities()->save($activity);
        return redirect()->route('Laralum::teacher::activity::list', ['teacher' => $teacher->id])->with('success', 'Teacher Activity Updated Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function experienceList(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['experiences'] = TeacherExperience::allWithOptionalFilter($request->search, $teacher->id, $request->status)->appends($request->all());
        $data['sortable'] = false;
        if (empty(array_filter($request->except('page')))) {
            $data['sortable'] = true;
        }

        return view('laralum.teacher.experience.index', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function experienceCreate(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        return view('laralum.teacher.experience.create', $data);
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function experienceStore(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'organization' => 'required',
        ]); */

        $experience = new TeacherExperience();
        $experience->organization = $request->organization;
        $experience->position = $request->position;
        $experience->description = $request->description;
        $experience->period = $request->period;
        $teacher->experiences()->save($experience);
        return redirect()->route('Laralum::teacher::experience::list', ['teacher' => $teacher->id])->with('success', 'Teacher Experience Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherExperience $experience
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function experienceDelete(Teacher $teacher, TeacherExperience $experience)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $experience->delete();
        return redirect()->route('Laralum::teacher::experience::list', ['teacher' => $teacher->id])->with('success', 'Experience Deleted Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherExperience $experience
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function experienceEdit(Teacher $teacher, TeacherExperience $experience)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['experience'] = $experience;
        return view('laralum.teacher.experience.edit', $data);
    }

    /**
     * @param Teacher $teacher
     * @param TeacherExperience $experience
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function experienceUpdate(Teacher $teacher, TeacherExperience $experience, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        /* $this->validate($request, [
            'organization' => 'required',
        ]); */

        $experience->organization = $request->organization;
        $experience->position = $request->position;
        $experience->description = $request->description;
        $experience->period = $request->period;
        $teacher->experiences()->save($experience);
        return redirect()->route('Laralum::teacher::experience::list', ['teacher' => $teacher->id])->with('success', 'Teacher Experience Updated Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publicationList(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['teacher'] = $teacher;
        $data['types'] = TeacherPublicationType::all();
        $data['publications'] = TeacherPublication::allWithOptionalFilter($request->search, $teacher->id, $request->teacher_publication_type_id, $request->status, false, 'typeInfo')->appends($request->all());
        $data['sortable'] = false;
        if (count(array_filter($request->except('page'))) == 1 && key(array_filter($request->except('page'))) == 'teacher_publication_type_id') {
            $data['sortable'] = true;
        }

        return view('laralum.teacher.publication.index', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publicationCreate(Teacher $teacher)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['types'] = TeacherPublicationType::all();
        $data['teacher'] = $teacher;
        return view('laralum.teacher.publication.create', $data);
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function publicationStore(Teacher $teacher, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $this->validate($request, [
            'teacher_publication_type_id' => 'required'
        ]);

        $publication = new TeacherPublication();
        $publication->name = $request->name;
        $publication->author_name = $request->author_name;
        $publication->publication_year = $request->publication_year;
        $publication->journal_name = $request->journal_name;
        $publication->conference_location = $request->conference_location;
        $publication->volume = $request->volume;
        $publication->issue = $request->issue;
        $publication->page = $request->page;
        $publication->teacher_publication_type_id = $request->teacher_publication_type_id;
        $publication->teacher_publication_type = TeacherPublicationType::find($request->teacher_publication_type_id, ['name'])->name;
        $publication->url = $request->url;
        $publication->url2 = $request->url2;
        $publication->description = $request->description;
        $publication->sorting_order = $request->sorting_order;

        $redirectParam = [
            'teacher' => $teacher->id,
            'teacher_publication_type_id' => $request->teacher_publication_type_id
        ];

        $teacher->publications()->save($publication);
        return redirect()->route('Laralum::teacher::publication::list', $redirectParam)->with('success', 'Teacher Publication Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherPublication $publication
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publicationDelete(Teacher $teacher, TeacherPublication $publication)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $publication->delete();
        return redirect()->route('Laralum::teacher::publication::list', ['teacher' => $teacher->id])->with('success', 'Publication deleted successfully');
    }

    /**
     * @param Teacher $teacher
     * @param TeacherPublication $publication
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publicationEdit(Teacher $teacher, TeacherPublication $publication)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $data['types'] = TeacherPublicationType::all();
        $data['teacher'] = $teacher;
        $data['publication'] = $publication;
        return view('laralum.teacher.publication.edit', $data);
    }

    /**
     * @param Teacher $teacher
     * @param TeacherPublication $publication
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function publicationUpdate(Teacher $teacher, TeacherPublication $publication, Request $request)
    {
        $this->authorize('TEACHER-SPECIFIC', $teacher->id);
        $this->validate($request, [
            'teacher_publication_type_id' => 'required'
        ]);

        $publication->name = $request->name;
        $publication->author_name = $request->author_name;
        $publication->publication_year = $request->publication_year;
        $publication->journal_name = $request->journal_name;
        $publication->conference_location = $request->conference_location;
        $publication->volume = $request->volume;
        $publication->issue = $request->issue;
        $publication->page = $request->page;
        $publication->teacher_publication_type_id = $request->teacher_publication_type_id;
        $publication->teacher_publication_type = TeacherPublicationType::find($request->teacher_publication_type_id, ['name'])->name;
        $publication->url = $request->url;
        $publication->url2 = $request->url2;
        $publication->description = $request->description;
        $publication->sorting_order = $request->sorting_order;
        $teacher->publications()->save($publication);

        $redirectParam = [
            'teacher' => $teacher->id,
            'teacher_publication_type_id' => $request->teacher_publication_type_id
        ];

        return redirect()->route('Laralum::teacher::publication::list', $redirectParam)->with('success', 'Teacher Publication Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = TeacherPublicationType::paginate();
        return view('laralum.teacher.publication.type.index', $data);
    }

    /**
     * @param TeacherPublicationType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(TeacherPublicationType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.teacher.publication.type.edit', $data);
    }

    /**
     * @param TeacherPublicationType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(TeacherPublicationType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::teacher::publication::type::list')->with('success', 'Teacher Publication Type Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type = new TeacherPublicationType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::teacher::publication::type::list')->with('success', 'Teacher Publication Type Created Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function jsonSearch(Request $request)
    {
        return Teacher::allWithOptionalFilter($request->search, $request->status, $request->department_id, $request->designation_id, $request->take, $request->relation);
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($slug)
    {
        $data['teacher'] = Teacher::with(['designationInfo', 'department', 'educations', 'teachings', 'experiences', 'activities', 'statusInfo'])->whereSlug($slug)->first();

        if (!$data['teacher']) {
            $data['teacher'] = Teacher::with(['designationInfo', 'department', 'educations', 'teachings', 'experiences', 'activities', 'statusInfo'])->where('email', 'LIKE', $slug . '@%')->first();
        }

        if ($data['teacher']) {
            $data['publicationsType'] = TeacherPublicationType::with(['publications' => function ($query) use ($data) {
                $query->whereTeacherId($data['teacher']->id);
            }])->whereHas('publications', function ($query) use ($data) {
                $query->whereTeacherId($data['teacher']->id);
            })->get();
        }

//        dd($data['teacher']->toArray());
        return view('frontend.teacher.view', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $data['faculties'] = Faculty::with('departments')->get();
        $data['statuses'] = TeacherStatus::all();
        $data['designations'] = TeacherDesignation::all();
        return view('frontend.teacher.list', $data);
    }

    /**
     * @param Teacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advanceOptionList(Teacher $teacher)
    {
        $data['teacher'] = $teacher;
        return view('laralum.teacher.advance-option', $data);
    }
}
