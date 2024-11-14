<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\UserTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('DEAN');
        $data['faculties'] = Faculty::allWithOptionalFilter($request->search, false, false, 'dean')->appends($request->all());
        $data['sortable'] = true;
        return view('laralum.faculty.index', $data);
    }

    /**
     * @param Faculty $faculty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignView(Faculty $faculty)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['faculty'] = $faculty;
        return view('laralum.faculty.assign', $data);
    }

    /**
     * @param Faculty $faculty
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assign(Faculty $faculty, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'teacher_id' => 'required'
        ]);

        $faculty->teacher_id = $request->teacher_id;
        $faculty->save();

        $userTeacher = UserTeacher::with('user')->whereTeacherId($faculty->teacher_id)->first();
        if ($userTeacher->user()->exists()) {
            $userTeacher->user->roles()->syncWithoutDetaching([4]);
            $userTeacher->user->faculties()->syncWithoutDetaching([$faculty->id]);
        }

        return redirect()->route('Laralum::faculty::list')->with('success', 'Dean Assigned Successfully');
    }

    /**
     * @param Faculty $faculty
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unassign(Faculty $faculty)
    {
        $this->authorize('ADMIN');

        $userTeacher = UserTeacher::with('user')->whereTeacherId($faculty->teacher_id)->first();
        if ($user = $userTeacher->user) {
            $user->faculties()->detach($faculty->id);
            ($user->faculties()->first()) ?: $user->roles()->detach(4);
        }

        $faculty->teacher_id = null;
        $faculty->save();
        return redirect()->route('Laralum::faculty::list')->with('success', 'Dean Un-Assigned Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.faculty.create');
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
            'type' => 'required',
            'slug' => 'required|unique:faculties,slug',
        ]);

        $faculty = new Faculty();
        $faculty->name = $request->name;
        $faculty->name_ben = $request->name_ben;
        $faculty->type = $request->type;
        $faculty->slug = $request->slug;
        $faculty->description = $request->description;
        $faculty->message_from_dean = $request->message_from_dean;
        $faculty->email = $request->email;
        $faculty->fax = $request->fax;
        $faculty->fabx = $request->fabx;
        $faculty->mobile_number = $request->mobile_number;
        $faculty->phone_number = $request->phone_number;
        $faculty->save();
        return redirect()->route('Laralum::faculty::list')->with('success', 'Faculty Created Successfully');
    }

    /**
     * @param Faculty $faculty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Faculty $faculty)
    {
        $this->authorize('DEAN-SPECIFIC', $faculty->id);
        $data['faculty'] = $faculty;
        return view('laralum.faculty.edit', $data);
    }

    /**
     * @param Faculty $faculty
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Faculty $faculty, Request $request)
    {
        $this->authorize('DEAN-SPECIFIC', $faculty->id);

        if (Auth::user()->can('ADMIN')) {
            $this->validate($request, [
                'name' => 'required',
                'type' => 'required',
                'slug' => [
                    'required',
                    Rule::unique('faculties')->ignore($faculty->id),
                ]
            ]);

            $faculty->name = $request->name;
            $faculty->type = $request->type;
            $faculty->slug = $request->slug;
        }

        $faculty->name_ben = $request->name_ben;
        $faculty->description = $request->description;
        $faculty->message_from_dean = $request->message_from_dean;
        $faculty->email = $request->email;
        $faculty->fax = $request->fax;
        $faculty->fabx = $request->fabx;
        $faculty->mobile_number = $request->mobile_number;
        $faculty->phone_number = $request->phone_number;
        $faculty->save();
        return redirect()->route('Laralum::faculty::list')->with('success', 'Faculty Updated Successfully');
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($slug)
    {
        $data['faculty'] = Faculty::getBySlug($slug);
        return view('frontend.faculty.view', $data);
    }

    public function jsonSearch(Request $request)
    {
        $query = Faculty::with('departments');
        return ($request->faculty_id) ? $query->whereId($request->faculty_id)->get() : $query->get();
    }
}
