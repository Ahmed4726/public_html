<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Research;
use App\ResearchType;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['types'] = ResearchType::all();
        $data['departments'] = Department::all();
        $data['researches'] = Research::allWithOptionalFilter($request->search, $request->department_id, $request->type_id, $request->status, false, 'department')->appends($request->all());
        (count(array_filter($request->except('page'))) == 0) ? $data['sortable'] = true : $data['sortable'] = false;
        return view('laralum.research.index', $data);
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentResearch($department, Request $request)
    {
        $data['uriValue'] = $department;
        if (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
            $data['researches'] = Research::allWithOptionalFilter($request->search, $department, $request->type_id, $request->status, false, 'department')->appends($request->all());
        }
        $data['sortable'] = true;
        $data['types'] = ResearchType::all();

        return view('laralum.research.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['types'] = ResearchType::all();
        $data['departments'] = Department::all();
        return view('laralum.research.create', $data);
    }

    /**
     * @param $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentResearchCreate($department)
    {
        $data['uriValue'] = $department;
        if (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
        }
        $data['types'] = ResearchType::all();

        return view('laralum.research.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
            'department_id' => 'required',
        ]);

        $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
        $research = new Research();
        $research->name = $request->name;
        $research->type_id = $request->type_id;
        $research->type = ResearchType::find($request->type_id, ['name'])->name;
        $research->department_id = $request->department_id;
        $research->website_link = $request->website_link;
        $research->description = $request->description;
        $research->message_from_editor = $request->message_from_editor;
        $research->enabled = (isset($request->enabled)) ? 1 : 0;
        $research->save();
//        return redirect()->route('Laralum::research::list')->with('success', 'Research Created Successfully');
        return redirect()->route('Laralum::department::research::list', ['department' => $request->department_id])->with('success', 'Research Created Successfully');
    }

    /**
     * @param Research $research
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Research $research)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $research->department_id);
        $research->delete();
        return redirect()->route('Laralum::department::research::list', ['department' => $research->department_id])->with('success', 'Research Deleted Successfully');
    }

    /**
     * @param Research $research
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Research $research)
    {
        if ($research->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $research->department_id);
            $data['uriValue'] = $research->department_id;
            $data['uri'] = 'department';
        }

        $data['faculties'] = Faculty::all();
        $data['research'] = $research;
        $data['types'] = ResearchType::all();
        $data['departments'] = Department::all();
        return view('laralum.research.edit', $data);
    }

    /**
     * @param Research $research
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Research $research, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required'
        ]);

        $this->authorize('DEPARTMENT-SPECIFIC', $research->department_id);
        $research->name = $request->name;
        $research->type_id = $request->type_id;
        $research->type = ResearchType::find($request->type_id, ['name'])->name;
        $research->website_link = $request->website_link;
        $research->description = $request->description;
        $research->message_from_editor = $request->message_from_editor;
        $research->enabled = (isset($request->enabled)) ? 1 : 0;
        $research->save();
//        return redirect()->route('Laralum::research::list')->with('success', 'Research Updated Successfully');
        return redirect()->route('Laralum::department::research::list', ['department' => $research->department_id])->with('success', 'Research Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = ResearchType::all();
        return view('laralum.research.type', $data);
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

        $type = new ResearchType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::research::type::list')->with('success', 'Research Type Created Successfully');
    }

    /**
     * @param ResearchType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(ResearchType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.research.type-edit', $data);
    }

    /**
     * @param ResearchType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(ResearchType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::research::type::list')->with('success', 'Research Type Updated Successfully');
    }

    public function view(Department $department, Research $research)
    {
    }

    public function show($research)
    {
        $data['research'] = Research::with('department', 'department.faculty')->find($research);
//        dd($data['research']);
        return view('frontend.research.view', $data);
    }

    public function showList(Request $request)
    {
        $data['researches'] = Research::allWithOptionalFilter(false, $request->department_id, false, 1, 50);
        return view('frontend.research.index', $data);
    }
}
