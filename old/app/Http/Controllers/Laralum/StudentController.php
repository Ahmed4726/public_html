<?php

namespace App\Http\Controllers\Laralum;

use App\AdmissionSession;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Student;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('ADMIN');
        $query = Student::with('admissionSession')->latest()->whereHall('New Hall')->get();
        dd($query->toArray());

        return view('laralum.student.index', [
            'sessions' => AdmissionSession::all(),
            'students' => QueryBuilder::for($query)
            ->allowedFilters([AllowedFilter::exact('admission_session_id')->ignore(null)])
            ->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.student.create', [
            'sessions' => AdmissionSession::all(),
            'student' => new Student
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        Student::create($request->validated());
        return redirect()->route('Laralum::student::list')->with('success', 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $this->authorize('ADMIN');
        return view('laralum.student.create', [
            'sessions' => AdmissionSession::all(),
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return redirect()->route('Laralum::student::list')->with('success', 'Student Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $this->authorize('ADMIN');
        $student->delete();
        return redirect()->route('Laralum::student::list')->with('success', 'Student Deleted Successfully');
    }
}
