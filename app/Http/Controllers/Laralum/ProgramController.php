<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\DepartmentProgram;
use App\Http\Controllers\Controller;
use App\ProgramType;
use Illuminate\Http\Request;
use Storage;

class ProgramController extends Controller
{
    public function departmentCenterHallList($departmentCenterHall, Request $request)
    {
        $data['uriValue'] = $departmentCenterHall;
        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenterHall);
            $data['programs'] = DepartmentProgram::allWithOptionalFilter($request->search, false, $departmentCenterHall, false, false, 1, false, ['typeInfo', 'department', 'center', 'hall'])->appends($request->all());
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenterHall);
            $data['uri'] = 'department';
            $data['programs'] = DepartmentProgram::allWithOptionalFilter($request->search, $departmentCenterHall, false, false, false, 1, false, ['typeInfo', 'department', 'center', 'hall'])->appends($request->all());
        } elseif (request()->is('admin/hall/*')) {
            $this->authorize('HALL-SPECIFIC', $departmentCenterHall);
            $data['uri'] = 'hall';
            $data['programs'] = DepartmentProgram::allWithOptionalFilter($request->search, false, false, $departmentCenterHall, false, 1, false, ['typeInfo', 'department', 'center', 'hall'])->appends($request->all());
        }
        $data['sortable'] = false;

        if (count(array_filter($request->except('page'))) == 0) {
            $data['sortable'] = true;
        }

        return view('laralum.program.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['types'] = ProgramType::all();
        $data['programs'] = DepartmentProgram::allWithOptionalFilter($request->search, false, false, false, 'global', $request->type_id, false, ['typeInfo', 'department', 'center', 'hall'])->appends($request->all());
        $data['sortable'] = false;
        $filterRequest = array_filter($request->except('page'));

        if (count($filterRequest) == 1 && array_key_exists('type_id', $filterRequest)) {
            $data['sortable'] = true;
        }

        return view('laralum.program.index', $data);
    }

    /**
     * @param $departmentCenterHall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createForCenterDepartmentHall($departmentCenterHall)
    {
        $data['uriValue'] = $departmentCenterHall;
        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenterHall);
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenterHall);
            $data['uri'] = 'department';
        } elseif (request()->is('admin/hall/*')) {
            $this->authorize('HALL-SPECIFIC', $departmentCenterHall);
            $data['uri'] = 'hall';
        }

        $data['types'] = ProgramType::all();
        return view('laralum.program.create', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['types'] = ProgramType::all();
        return view('laralum.program.create', $data);
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
            //            'slug' => 'required|unique:department_programs,slug'
        ]);

        $program = new DepartmentProgram();
        $program->name = $request->name;
        $program->slug = $request->slug;
        $program->external_link = $request->external_link;
        $program->description = $request->description;
        $program->enabled = (isset($request->enabled)) ? 1 : 0;
        $program->type_id = 1;

        $program->department_id = null;
        $program->center_id = null;
        $program->hall_id = null;

        if (isset($request->department_id)) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $program->department_id = $request->department_id;
            $uri = 'department';
            $redirectParam['department'] = $request->department_id;

            if (DepartmentProgram::whereDepartmentId($request->department_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if (isset($request->center_id)) {
            $this->authorize('CENTER-SPECIFIC', $request->center_id);
            $program->center_id = $request->center_id;
            $uri = 'center';
            $redirectParam['center'] = $request->center_id;

            if (DepartmentProgram::whereCenterId($request->center_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if (isset($request->hall_id)) {
            $this->authorize('HALL-SPECIFIC', $request->hall_id);
            $program->hall_id = $request->hall_id;
            $uri = 'hall';
            $redirectParam['hall'] = $request->hall_id;

            if (DepartmentProgram::whereHallId($request->hall_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if (!$program->department_id && !$program->center_id && !$program->hall_id) {
            $this->authorize('ADMIN');
            $this->validate($request, [
                'type_id' => 'required',
            ]);
            $program->type_id = $request->type_id;
            $program->type = ProgramType::find($request->type_id, ['name'])->name;
            $redirectParam['type_id'] = $request->type_id;

            if (DepartmentProgram::whereTypeId($request->type_id)->whereNull('hall_id')->whereNull('center_id')->whereNull('department_id')->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if ($request->hasFile('file')) {
            $request->file->store('public/image/program');
            $program->path = $request->file->hashName();
        }

        $program->save();

        if (isset($uri)) {
            return redirect()->route("Laralum::$uri::program::list", $redirectParam)->with('success', 'Program Created Successfully');
        } else {
            return redirect()->route('Laralum::program::list', $redirectParam)->with('success', 'Program / Calender Created Successfully');
        }
    }

    /**
     * @param DepartmentProgram $program
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(DepartmentProgram $program)
    {
        if ($program->center_id) {
            $this->authorize('CENTER-SPECIFIC', $program->center_id);
            $data['uriValue'] = $program->center_id;
            $data['uri'] = 'center';
        } elseif ($program->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $program->department_id);
            $data['uriValue'] = $program->department_id;
            $data['uri'] = 'department';
        } elseif ($program->hall_id) {
            $this->authorize('HALL-SPECIFIC', $program->hall_id);
            $data['uriValue'] = $program->hall_id;
            $data['uri'] = 'hall';
        }

        $data['program'] = $program;
        $data['types'] = ProgramType::all();
        return view('laralum.program.edit', $data);
    }

    /**
     * @param DepartmentProgram $program
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(DepartmentProgram $program)
    {
        if ($program->center_id) {
            $this->authorize('CENTER-SPECIFIC', $program->center_id);
        } elseif ($program->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $program->department_id);
        } elseif ($program->hall_id) {
            $this->authorize('HALL-SPECIFIC', $program->hall_id);
        }

        $program->delete();
        if ($program->center_id) {
            return redirect()->route('Laralum::center::program::list', ['center' => $program->center_id])->with('success', 'Program Deleted Successfully');
        } elseif ($program->department_id) {
            return redirect()->route('Laralum::department::program::list', ['department' => $program->department_id])->with('success', 'Program Deleted Successfully');
        } elseif ($program->hall_id) {
            return redirect()->route('Laralum::hall::program::list', ['hall' => $program->hall_id])->with('success', 'Program Deleted Successfully');
        }
    }

    /**
     * @param DepartmentProgram $program
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DepartmentProgram $program, Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $program->name = $request->name;
        $program->slug = $request->slug;
        $program->external_link = $request->external_link;
        $program->description = $request->description;
        $program->enabled = (isset($request->enabled)) ? 1 : 0;

        if ($program->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $program->department_id);
            $uri = 'department';
            $redirectParam['department'] = $program->department_id;

            if (DepartmentProgram::where('id', '!=', $program->id)->whereDepartmentId($program->department_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if ($program->center_id) {
            $this->authorize('CENTER-SPECIFIC', $program->center_id);
            $uri = 'center';
            $redirectParam['center'] = $program->center_id;

            if (DepartmentProgram::where('id', '!=', $program->id)->whereCenterId($program->center_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if ($program->hall_id) {
            $this->authorize('HALL-SPECIFIC', $program->hall_id);
            $uri = 'hall';
            $redirectParam['hall'] = $program->hall_id;

            if (DepartmentProgram::where('id', '!=', $program->id)->whereHallmentId($program->hall_id)->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if (!$program->department_id && !$program->center_id && !$program->hall_id) {
            $this->authorize('ADMIN');
            $this->validate($request, [
                'type_id' => 'required',
            ]);
            $program->type_id = $request->type_id;
            $program->type = ProgramType::find($request->type_id, ['name'])->name;
            $redirectParam['type_id'] = $request->type_id;

            if (DepartmentProgram::where('id', '!=', $program->id)->whereTypeId($request->type_id)->whereNull('hall_id')->whereNull('center_id')->whereNull('department_id')->whereSlug($request->slug)->exists()) {
                return redirect()->back()->with('error', 'Slug Already Exists');
            }
        }

        if ($request->hasFile('file')) {
            $request->file->store('public/image/program');
            if ($program->path) {
                Storage::delete("public/image/program/$program->path");
            }
            $program->path = $request->file->hashName();
        }

        $program->save();
        if (isset($uri)) {
            return redirect()->route("Laralum::$uri::program::list", $redirectParam)->with('success', 'Program Updated Successfully');
        } else {
            return redirect()->route('Laralum::program::list', $redirectParam)->with('success', 'Program / Calender Updated Successfully');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = ProgramType::all();
        return view('laralum.program.type', $data);
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

        $type = new ProgramType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::program::type::list')->with('success', 'Program / Calender Type Created Successfully');
    }

    /**
     * @param ProgramType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(ProgramType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.program.type-edit', $data);
    }

    /**
     * @param ProgramType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(ProgramType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::program::type::list')->with('success', 'Program / Calender Type Updated Successfully');
    }

    /**
     * @param $program
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($program)
    {
        $data['program'] = (is_numeric($program)) ? DepartmentProgram::find($program) : DepartmentProgram::whereSlug($program)->first();
        return view('frontend.program.view', $data);
    }

    /**
     * @param $center
     * @param $program
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function centerView($center, $program)
    {
        $data['program'] = DepartmentProgram::whereHas('center', function ($query) use ($center) {
            $query->where('slug', $center);
        });

        $data['program'] = is_numeric($program) ? $data['program']->whereId($program)->first() : $data['program']->whereSlug($program)->first();
        return view('frontend.program.view', $data);
    }

    public function departmentView($department, $program)
    {
        $data['program'] = DepartmentProgram::whereHas('department', function ($query) use ($department) {
            $query->where('slug', $department);
        });

        $data['program'] = is_numeric($program) ? $data['program']->whereId($program)->first() : $data['program']->whereSlug($program)->first();
        return view('frontend.program.view', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calenderView(Request $request)
    {
        $data['calenders'] = DepartmentProgram::allWithOptionalFilter(false, false, false, false, false, 2, false, false, ['enabled' => 1]);
        $data['currentCalender'] = ($request->has('id')) ? $data['calenders']->where('id', $request->id)->first() : $data['calenders']->first() ;

        return view('frontend.program.calender-view', $data);
    }

    /**
     * @param DepartmentProgram $program
     * @return mixed
     */
    public function fileView(DepartmentProgram $program)
    {
        return response()->file(public_path("storage/image/program/$program->path"));
    }

    public function search(Request $request)
    {
        return DepartmentProgram::allWithOptionalFilter(false, $request->department_id);
    }

    public function showList(Request $request)
    {
        $data['programs'] = DepartmentProgram::allWithOptionalFilter(false, $request->department_id, $request->center_id, $request->hall_id, false, 1, 50, ['department', 'department.faculty', 'center', 'hall'], ['enabled' => 1]);
        return view('frontend.program.index', $data);
    }
}
