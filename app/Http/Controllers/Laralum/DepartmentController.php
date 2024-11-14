<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\DepartmentFile;
use App\DepartmentProgram;
use App\DiscussionTopic;
use App\Event;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\LinkType;
use App\Setting;
use App\UserTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Storage;

class DepartmentController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('DEPARTMENT');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::allWithOptionalFilter($request->search, $request->faculty_id, false, ['faculty', 'chairman'])->appends($request->all());
        $data['sortable'] = false;
        if (count(array_filter($request->except('page'))) == 1 && key(array_filter($request->except('page'))) == 'faculty_id') {
            $data['sortable'] = true;
        }

        return view('laralum.department.index', $data);
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Department $department)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $data['department'] = $department;
        $data['faculties'] = Faculty::all();
        return view('laralum.department.edit', $data);
    }

    /**
     * @param Department $department
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Department $department, Request $request)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $this->validate($request, [
            'short_name' => 'required'
        ]);

        if (Auth::user()->can('ADMIN')) {
            $this->validate($request, [
                'name' => 'required',
                'faculty_id' => 'required',
                'slug' => ['required',  Rule::unique('departments')->ignore($department->id), ]
            ]);
            $department->name = $request->name;
            $department->faculty_id = $request->faculty_id;
            $department->slug = $request->slug;
        }

        $department->name_ben = $request->name_ben;
        $department->short_name = $request->short_name;
        $department->short_name_ben = $request->short_name_ben;
        $department->dob = ($request->dob) ? date('Y-m-d', strtotime($request->dob)) : null;
        $department->contact_email = $request->contact_email;
        $department->contact_mobile_number = $request->contact_mobile_number;
        $department->contact_phone_number = $request->contact_phone_number;
        $department->description = $request->description;
        $department->message_from_chairman = $request->message_from_chairman;

        if ($request->config) {
            try {
                $department->config = \Laralum::configTextToArray($request->config);
            } catch (\Throwable $exception) {
                echo $exception->getMessage();
            }
        }

        $department->external_link = $request->external_link;

        ($department->image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = 500;
            $height = 400;
            $width = 600;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/department/$fileName", $width, $height);
            if ($department->image_url) {
                Storage::delete("public/image/department/$department->image_url");
            }
            $department->image_url = $fileName;
        }

        $department->save();
        return redirect()->route('Laralum::department::list')->with('success', 'Department Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        return view('laralum.department.create', $data);
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
            'short_name' => 'required',
            'faculty_id' => 'required',
            'slug' => 'required|unique:departments,slug'
        ]);

        $department = new Department();
        $department->name = $request->name;
        $department->name_ben = $request->name_ben;
        $department->short_name = $request->short_name;
        $department->short_name_ben = $request->short_name_ben;
        $department->faculty_id = $request->faculty_id;
        $department->slug = $request->slug;
        if ($request->dob) {
            $department->dob = date('Y-m-d', strtotime($request->dob));
        }
        $department->contact_email = $request->contact_email;
        $department->contact_mobile_number = $request->contact_mobile_number;
        $department->contact_phone_number = $request->contact_phone_number;
        $department->description = $request->description;
        $department->message_from_chairman = $request->message_from_chairman;

        try {
            $department->config = \Laralum::configTextToArray($request->config);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $department->external_link = $request->external_link;

        if ($request->hasFile('image')) {
            $maxSize = 500;
            $height = 400;
            $width = 600;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/department/$fileName", $width, $height);
            $department->image_url = $fileName;
        }

        $department->save();
        return redirect()->route('Laralum::department::list')->with('success', 'Department Created Successfully');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignView(Department $department)
    {
        $this->authorize('ADMIN');
        $data['departments'] = Department::all();
        $data['faculties'] = Faculty::all();
        $data['department'] = $department;
        return view('laralum.department.assign', $data);
    }

    /**
     * @param Department $department
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assign(Department $department, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'teacher_id' => 'required'
        ]);
        $department->teacher_id = $request->teacher_id;
        $department->save();

        $userTeacher = UserTeacher::with('user')->whereTeacherId($department->teacher_id)->first();
        if ($userTeacher->user()->exists()) {
            $userTeacher->user->roles()->syncWithoutDetaching([6]);
            $userTeacher->user->departments()->syncWithoutDetaching([$department->id]);
        }

        return redirect()->route('Laralum::department::list')->with('success', 'Chairman Assigned Successfully');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unassign(Department $department)
    {
        $this->authorize('ADMIN');

        $userTeacher = UserTeacher::with('user')->whereTeacherId($department->teacher_id)->first();
        if ($userTeacher->user()->exists()) {
            $userTeacher->user->departments()->detach($department->id);
            ($userTeacher->user->departments()->first()) ?: $userTeacher->user->roles()->detach(6);
        }

        $department->teacher_id = null;
        $department->save();

        return redirect()->back()->with('success', 'Chairman Un-Assigned Successfully');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function upload(Department $department)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $data['files'] = DepartmentFile::whereDepartmentId($department->id)->get();
        $data['department'] = $department;
        return view('laralum.department.upload', $data);
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadCreate(Department $department)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $data['files'] = DepartmentFile::whereDepartmentId($department->id)->get();
        $data['department'] = $department;
        return view('laralum.department.upload-create', $data);
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadSave($department, Request $request)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department);
        $this->validate($request, [
            'name' => 'required',
            'file' => 'required'
        ]);

        $departmentFile = new DepartmentFile();
        $departmentFile->department_id = $department;
        $departmentFile->name = $request->name;
        $departmentFile->type = $request->type;
        $departmentFile->description = $request->description;
        $departmentFile->listing_enabled = (isset($request->listing_enabled)) ? 1 : 0;
        $request->file->store("public/image/department/$department");
        $departmentFile->path = $request->file->hashName();
        $departmentFile->save();
        return redirect()->route('Laralum::department::upload', ['department' => $department])->with('success', 'File Uploaded Successfully');
    }

    /**
     * @param Department $department
     * @param DepartmentFile $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadDelete(Department $department, DepartmentFile $file)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $file->delete();
        return redirect()->route('Laralum::department::upload', ['department' => $department->id])->with('success', 'Upload file deleted successfully');
    }

    /**
     * @param Department $department
     * @param DepartmentFile $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadEdit(Department $department, DepartmentFile $file)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department->id);
        $data['file'] = $file;
        $data['department'] = $department;
        return view('laralum.department.upload-edit', $data);
    }

    /**
     * @param $department
     * @param DepartmentFile $file
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadUpdate($department, DepartmentFile $file, Request $request)
    {
        $this->authorize('DEPARTMENT-SPECIFIC', $department);
        $this->validate($request, [
            'name' => 'required',
        ]);

        $file->department_id = $department;
        $file->name = $request->name;
        $file->type = $request->type;
        $file->description = $request->description;
        $file->listing_enabled = (isset($request->listing_enabled)) ? 1 : 0;

        if ($request->hasFile('file')) {
            $request->file->store("public/image/department/$department");
            if ($file->path) {
                Storage::delete("public/image/department/$department/$file->path");
            }
            $file->path = $request->file->hashName();
        }

        $file->save();
        return redirect()->route('Laralum::department::upload', ['department' => $department])->with('success', 'Uploaded File Updated Successfully');
    }

    /**
     * @param $department
     * @param DepartmentFile $file
     * @return mixed
     */
    public function fileView($department, DepartmentFile $file)
    {
        return response()->download(public_path("storage/image/department/$department/$file->path"));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function jsonSearch(Request $request)
    {
        return DepartmentResource::collection(Department::allWithOptionalFilter(false, $request->faculty_id, 5000));
    }

    public function view($slug)
    {
        return $slug;
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function instituteView($slug)
    {
        $data['department'] = Department::getDetailsBySlug($slug);

        if ($data['department']) {
            $data['events'] = Event::listWithTopics(Setting::first()->department_event, $data['department']->id);
        }

        return view('frontend.department.institute-view', $data);
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chairmanMessageView($department)
    {
        $data['department'] = Department::with(['chairman', 'chairman.designationInfo', 'faculty'])->find($department);
        return view('frontend.department.chairman-message', $data);
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function linkListsByDepartment(Department $department)
    {
        $data['department'] = $department;

        if ($department) {
            $data['links'] = LinkType::with(['links' => function ($query) use ($department) {
                $query->whereDepartmentId($department->id)->whereEnabled(1);
            }])->whereHas('links', function ($query) use ($department) {
                $query->whereDepartmentId($department->id)->whereEnabled(1);
            })->get();
        }

        return view('frontend.department.link-list-view', $data);
    }

    public function programView(Department $department, DepartmentProgram $program)
    {
    }

    /**
     * @param $discussion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discussionView($discussion)
    {
        $data['discussion'] = DiscussionTopic::with(['event', 'files', 'department', 'department.faculty'])->find($discussion);

        if (!$data['discussion']) {
            return redirect()->route('Frontend::not-found');
        }

        $departmentID = ($data['discussion']->department()->exists()) ? $data['discussion']->department->id : false;
        $data['relatedDiscussion'] = DiscussionTopic::allWithOptionalFilter(false, $departmentID, $data['discussion']->event->id, 1, false, false, 6);

        return view('frontend.department.discussion-view', $data);
    }

    /**
     * @param $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fileLists($department)
    {
        $data['department'] = Department::with(['files' => function ($query) {
            $query->whereListingEnabled(1);
        }])->find($department);

        return view('frontend.department.download-list-view', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discussionList(Request $request)
    {
        /*$request->departmentId;
        $request->type;*/
        $data['events'] = Event::all();
        return view('frontend.department.event-discussion-list', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function discussionSearch(Request $request)
    {
        $options = [];
        $eventId = $request->event_id;

        if ($request->event_id == 'featured') {
            $eventId = false;
            $options['featured_news'] = 1;
        }

        return DiscussionTopic::allWithOptionalFilter($request->search, $request->department_id, $eventId, 1, $request->fromDate, $request->toDate, false, false, $options);
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advanceOptionList(Department $department)
    {
        $data['department'] = $department;
        return view('laralum.department.advance-option', $data);
    }
}
