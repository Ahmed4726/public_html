<?php

namespace App\Http\Controllers\Laralum;

use App\Center;
use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Officer;
use App\OfficerType;
use App\TeacherStatus;
use Illuminate\Http\Request;
use Laralum;
use Storage;

class OfficerController extends Controller
{
    public function departmentCenterOfficerList($departmentCenter, Request $request)
    {
        $data['statuses'] = TeacherStatus::all();
        $data['types'] = OfficerType::all();
        $data['sortable'] = false;
        $data['uriValue'] = $departmentCenter;

        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenter);
            $data['officers'] = Officer::allWithOptionalFilter($request->search, $request->status, false, $departmentCenter, $request->type_id, false, ['statusInfo', 'department', 'center', 'typeInfo'])->appends($request->all());
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenter);
            $data['uri'] = 'department';
            $data['officers'] = Officer::allWithOptionalFilter($request->search, $request->status, $departmentCenter, false, $request->type_id, false, ['statusInfo', 'department', 'center', 'typeInfo'])->appends($request->all());
        }

        $filterRequest = array_filter($request->except('page'));
        if (count($filterRequest) == 1 && array_key_exists('type_id', $filterRequest)) {
            $data['sortable'] = true;
        }

        return view('laralum.officer.index', $data);
    }

    public function index(Request $request)
    {
        $data['statuses'] = TeacherStatus::all();
        $data['departments'] = Department::all();
        $data['centers'] = Center::all();
        $data['types'] = OfficerType::all();
        $data['officers'] = Officer::allWithOptionalFilter($request->search, $request->status, $request->department_id, $request->center_id, $request->type_id, false, ['statusInfo', 'department', 'center', 'typeInfo'])->appends($request->all());
        $data['sortable'] = false;
        $filterRequest = array_filter($request->except('page'));

        if (count($filterRequest) == 2 && array_key_exists('type_id', $filterRequest)) {
            if (array_key_exists('department_id', $filterRequest)) {
                $data['sortable'] = true;
            } elseif (array_key_exists('center_id', $filterRequest)) {
                $data['sortable'] = true;
            }
        }

        return view('laralum.officer.index_old', $data);
    }

    /**
     * @param $departmentCenter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($departmentCenter)
    {
        $data['uriValue'] = $departmentCenter;
        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenter);
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenter);
            $data['uri'] = 'department';
        }

        $data['types'] = OfficerType::all();
        $data['statuses'] = TeacherStatus::all();
        return view('laralum.officer.create', $data);
    }

    public function createOld($departmentCenter)
    {
        $data['faculties'] = Faculty::all();
        $data['types'] = OfficerType::all();
        $data['statuses'] = TeacherStatus::all();
        $data['departments'] = Department::all();
        $data['centers'] = Center::all();
        return view('laralum.officer.create_old', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = OfficerType::all();
        return view('laralum.officer.type', $data);
    }

    /**
     * @param OfficerType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(OfficerType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.officer.type-edit', $data);
    }

    /**
     * @param OfficerType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(OfficerType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'max_size' => 'required',
            'width' => 'required',
            'height' => 'required',
        ]);

        $type->name = $request->name;
        $type->max_size = $request->max_size;
        $type->width = $request->width;
        $type->height = $request->height;
        $type->save();
        return redirect()->route('Laralum::officer::type::list')->with('success', 'Officers / Staffs Type Updated Successfully');
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
            'max_size' => 'required',
            'width' => 'required',
            'height' => 'required',
        ]);

        $type = new OfficerType();
        $type->name = $request->name;
        $type->max_size = $request->max_size;
        $type->width = $request->width;
        $type->height = $request->height;
        $type->save();
        return redirect()->route('Laralum::officer::type::list')->with('success', 'Officers / Staffs Type Created Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'type_id' => 'required',
            'name' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $type = OfficerType::find($request->type_id, ['max_size', 'height', 'width', 'name']);
        $officer = new Officer();
        $officer->status = $request->status;
        $officer->type_id = $request->type_id;
        $officer->type = $type->name;
        if ($request->sorting_order) {
            $officer->sorting_order = $request->sorting_order;
        }

        $officer->department_id = null;
        $officer->center_id = null;

        if (isset($request->department_id)) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $officer->department_id = $request->department_id;
            $uri = 'department';
            $redirectParam['department'] = $request->department_id;
        }

        if (isset($request->center_id)) {
            $this->authorize('CENTER-SPECIFIC', $request->center_id);
            $officer->center_id = $request->center_id;
            $uri = 'center';
            $redirectParam['center'] = $request->center_id;
        }

        $officer->name = $request->name;
        $officer->name_ben = $request->name_ben;
        $officer->email = $request->email;
        $officer->designation = $request->designation;
        $officer->department_name = $request->department_name;
        $officer->work_phone = $request->work_phone;
        $officer->home_phone = $request->home_phone;
        $officer->external_link = $request->external_link;

        if ($request->hasFile('image')) {
            $maxSize = $type->max_size;
            $height = $type->height;
            $width = $type->width;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/officer/$fileName", $width, $height);
            $officer->image_url = $fileName;
        }

        $officer->save();
        return redirect()->route("Laralum::$uri::officer::list", $redirectParam)->with('success', 'Employee Created Successfully');
    }

    /**
     * @param Officer $officer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Officer $officer)
    {
        if ($officer->center_id) {
            $this->authorize('CENTER-SPECIFIC', $officer->center_id);
        } elseif ($officer->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $officer->department_id);
        }

        $officer->delete();
        if ($officer->center_id) {
            return redirect()->route('Laralum::center::officer::list', ['center' => $officer->center_id])->with('success', 'Employee Deleted Successfully');
        } elseif ($officer->department_id) {
            return redirect()->route('Laralum::department::officer::list', ['department' => $officer->department_id])->with('success', 'Employee Deleted Successfully');
        }
    }

    /**
     * @param Officer $officer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Officer $officer)
    {
        if ($officer->center_id) {
            $this->authorize('CENTER-SPECIFIC', $officer->center_id);
            $data['uriValue'] = $officer->center_id;
            $data['uri'] = 'center';
        } elseif ($officer->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $officer->department_id);
            $data['uriValue'] = $officer->department_id;
            $data['uri'] = 'department';
        }

        $data['faculties'] = Faculty::all();
        $data['types'] = OfficerType::all();
        $data['statuses'] = TeacherStatus::all();
        $data['departments'] = Department::all();
        $data['centers'] = Center::all();
        $data['officer'] = $officer;
        return view('laralum.officer.edit', $data);
    }

    /**
     * @param Officer $officer
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Officer $officer, Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'type_id' => 'required',
            'name' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $type = OfficerType::find($request->type_id, ['max_size', 'height', 'width', 'name']);
        $officer->status = $request->status;
        $officer->type_id = $request->type_id;
        $officer->type = $type->name;
        $officer->sorting_order = $request->sorting_order;

        if ($officer->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $officer->department_id);
            $uri = 'department';
            $redirectParam['department'] = $officer->department_id;
        }

        if ($officer->center_id) {
            $this->authorize('CENTER-SPECIFIC', $officer->center_id);
            $uri = 'center';
            $redirectParam['center'] = $officer->center_id;
        }

        $officer->name = $request->name;
        $officer->name_ben = $request->name_ben;
        $officer->email = $request->email;
        $officer->designation = $request->designation;
        $officer->department_name = $request->department_name;
        $officer->work_phone = $request->work_phone;
        $officer->home_phone = $request->home_phone;
        $officer->external_link = $request->external_link;

        ($officer->image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = $type->max_size;
            $height = $type->height;
            $width = $type->width;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/officer/$fileName", $width, $height);
            if ($officer->image_url) {
                Storage::delete("public/image/officer/$officer->image_url");
            }
            $officer->image_url = $fileName;
        }

        $officer->save();
        return redirect()->route("Laralum::$uri::officer::list", $redirectParam)->with('success', 'Employee Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $data['centers'] = Center::all();
        $data['faculties'] = Faculty::with('departments')->get();
        $data['types'] = OfficerType::all();
        return view('frontend.officer.list', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(Request $request)
    {
        return Officer::allWithOptionalFilter($request->search, $request->status, $request->department_id, $request->center_id, $request->type_id, false, $request->relation);
    }
}
