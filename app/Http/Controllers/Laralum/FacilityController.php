<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Facility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use Laralum;

class FacilityController extends Controller
{
    public function departmentCenterList($departmentCenter, Request $request)
    {
        $data['uriValue'] = $departmentCenter;
        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenter);
            $data['facilities'] = Facility::allWithOptionalFilter($request->search, false, $departmentCenter, false, $request->status, false, ['department', 'center'])->appends($request->all());
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenter);
            $data['uri'] = 'department';
            $data['facilities'] = Facility::allWithOptionalFilter($request->search, $departmentCenter, false, false, $request->status, false, ['department', 'center'])->appends($request->all());
        }
        $data['sortable'] = false;

        if (count(array_filter($request->except('page'))) == 0) {
            $data['sortable'] = true;
        }

        return view('laralum.facility.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['facilities'] = Facility::allWithOptionalFilter($request->search, false, false, 'global', $request->status, false, ['department', 'center'])->appends($request->all());
        $data['sortable'] = false;

        if (count(array_filter($request->except('page'))) == 0) {
            $data['sortable'] = true;
        }

        return view('laralum.facility.index', $data);
    }

    /**
     * @param $departmentCenter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createForCenterDepartment($departmentCenter)
    {
        $data['uriValue'] = $departmentCenter;
        if (request()->is('admin/center/*')) {
            $this->authorize('CENTER-SPECIFIC', $departmentCenter);
            $data['uri'] = 'center';
        } elseif (request()->is('admin/department/*')) {
            $this->authorize('DEPARTMENT-SPECIFIC', $departmentCenter);
            $data['uri'] = 'department';
        }

        return view('laralum.facility.create', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.facility.create');
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
        ]);

        $facility = new Facility();
        $facility->name = $request->name;
        $facility->external_link = $request->external_link;
        $facility->description = $request->description;
        $facility->contact = $request->contact;
        $facility->enabled = (isset($request->enabled)) ? 1 : 0;

        $facility->department_id = null;
        $facility->center_id = null;

        if (isset($request->department_id)) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $facility->department_id = $request->department_id;
            $uri = 'department';
            $redirectParam['department'] = $request->department_id;
        }

        if (isset($request->center_id)) {
            $this->authorize('CENTER-SPECIFIC', $request->center_id);
            $facility->center_id = $request->center_id;
            $uri = 'center';
            $redirectParam['center'] = $request->center_id;
        }

        if ($request->hasFile('image')) {
            $maxSize = 3000;
            $height = 800;
            $width = 1200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/facility/$fileName", $width, $height);
            $facility->image_url = $fileName;
        }

        $facility->save();
        if (isset($uri)) {
            return redirect()->route("Laralum::$uri::facility::list", $redirectParam)->with('success', 'Facility Created Successfully');
        } else {
            return redirect()->route('Laralum::facility::list')->with('success', 'Facility Created Successfully');
        }
    }

    /**
     * @param Facility $facility
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Facility $facility)
    {
        if ($facility->center_id) {
            $this->authorize('CENTER-SPECIFIC', $facility->center_id);
        } elseif ($facility->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $facility->department_id);
        }

        $facility->delete();
        if ($facility->center_id) {
            return redirect()->route('Laralum::center::facility::list', ['center' => $facility->center_id])->with('success', 'Facility Deleted Successfully');
        } elseif ($facility->department_id) {
            return redirect()->route('Laralum::department::facility::list', ['department' => $facility->department_id])->with('success', 'Facility Deleted Successfully');
        }
    }

    /**
     * @param Facility $facility
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Facility $facility)
    {
        if ($facility->center_id) {
            $this->authorize('CENTER-SPECIFIC', $facility->center_id);
            $data['uriValue'] = $facility->center_id;
            $data['uri'] = 'center';
        } elseif ($facility->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $facility->department_id);
            $data['uriValue'] = $facility->department_id;
            $data['uri'] = 'department';
        }

        $data['facility'] = $facility;
        return view('laralum.facility.edit', $data);
    }

    /**
     * @param Facility $facility
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Facility $facility, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $facility->name = $request->name;
        $facility->external_link = $request->external_link;
        $facility->description = $request->description;
        $facility->contact = $request->contact;
        $facility->enabled = (isset($request->enabled)) ? 1 : 0;

        if ($facility->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $facility->department_id);
            $uri = 'department';
            $redirectParam['department'] = $facility->department_id;
        }

        if ($facility->center_id) {
            $this->authorize('CENTER-SPECIFIC', $facility->center_id);
            $uri = 'center';
            $redirectParam['department'] = $facility->center_id;
        }

        ($facility->image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = 3000;
            $height = 800;
            $width = 1200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/facility/$fileName", $width, $height);
            if ($facility->image_url) {
                Storage::delete("public/image/facility/$facility->image_url");
            }
            $facility->image_url = $fileName;
        }

        $facility->save();
        if (isset($uri)) {
            return redirect()->route("Laralum::$uri::facility::list", $redirectParam)->with('success', 'Facility Updated Successfully');
        } else {
            return redirect()->route('Laralum::facility::list')->with('success', 'Facility Updated Successfully');
        }
    }

    public function departmentView(Department $department, Facility $facility)
    {
    }

    public function view($facility)
    {
        $data['facility'] = Facility::with(['center', 'department', 'department.faculty'])->find($facility);
        return view('frontend.facility.view', $data);
    }

    public function showList(Request $request)
    {
        $data['facilities'] = Facility::allWithOptionalFilter(false, $request->department_id, $request->center_id, false, 1, 50);
        return view('frontend.facility.index', $data);
    }
}
