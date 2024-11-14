<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Faculty;
use App\Hall;
use App\Http\Controllers\Controller;
use App\UserTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Laralum;
use Storage;

class HallController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('HALL');
        $data['halls'] = Hall::allWithOptionalFilter($request->search, false, 'teacher')->appends($request->all());
        (empty(array_filter($request->except('page')))) ? $data['sortable'] = true : $data['sortable'] = false;
        return view('laralum.hall.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.hall.create');
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
           'slug' => 'required|unique:halls,slug'
        ]);

        $hall = new Hall();
        $hall->name = $request->name;
        $hall->name_ben = $request->name_ben;
        $hall->short_name = $request->short_name;
        $hall->short_name_ben = $request->short_name_ben;
        $hall->slug = $request->slug;
        $hall->description = $request->description;
        $hall->provost_label = $request->provost_label;
        $hall->message_from_provost_label = $request->message_from_provost_label;
        $hall->message_from_provost = $request->message_from_provost;
        $hall->dob = date('Y-m-d', strtotime($request->dob));

        if($request->hasFile('image')){
            $maxSize = 2000;
            $height = 540;
            $width = 960;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/hall/$fileName", $width, $height);
            $hall->image_url = $fileName;
        }

        $hall->save();
        return redirect()->route('Laralum::hall::list')->with('success', 'Hall Created Successfully');
    }

    /**
     * @param Hall $hall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Hall $hall)
    {
        $this->authorize('HALL-SPECIFIC', $hall->id);
        $data['hall'] = $hall;
        return view('laralum.hall.edit', $data);
    }

    /**
     * @param Hall $hall
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Hall $hall, Request $request)
    {
        $this->authorize('HALL-SPECIFIC', $hall->id);

        if(Auth::user()->can('ADMIN')) {
            $this->validate($request, [
                'name' => 'required',
                'slug' => [ 'required', Rule::unique('halls')->ignore($hall->id)]
            ]);
            $hall->name = $request->name;
            $hall->slug = $request->slug;
        }

        $hall->name_ben = $request->name_ben;
        $hall->short_name = $request->short_name;
        $hall->short_name_ben = $request->short_name_ben;
        $hall->description = $request->description;
        $hall->provost_label = $request->provost_label;
        $hall->message_from_provost_label = $request->message_from_provost_label;
        $hall->message_from_provost = $request->message_from_provost;
        $hall->dob = date('Y-m-d', strtotime($request->dob));

        ($hall->image_url == $request->path) ? $imageUploadRequired = false :  $imageUploadRequired = true;
        if($request->hasFile('image') && $imageUploadRequired){
            $maxSize = 2000;
            $height = 540;
            $width = 960;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/hall/$fileName", $width, $height);
            if($hall->image_url) Storage::delete("public/image/hall/$hall->image_url");
            $hall->image_url = $fileName;
        }

        $hall->save();
        return redirect()->route('Laralum::hall::list')->with('success', 'Hall Updated Successfully');
    }

    public function delete (Hall $hall)
    {
        $this->authorize('ADMIN');
        $hall->delete();
        return redirect()->route('Laralum::hall::list')->with('success', 'Hall Deleted Successfully');
    }

    /**
     * @param Hall $hall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assign(Hall $hall)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['hall'] = $hall;
        return view('laralum.hall.assign', $data);
    }

    /**
     * @param Hall $hall
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignSave(Hall $hall, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
           'teacher_id' => 'required'
        ]);

        $hall->teacher_id = $request->teacher_id;
        $hall->save();

        $userTeacher = UserTeacher::with('user')->whereTeacherId($hall->teacher_id)->first();
        if($userTeacher->user()->exists()){
            $userTeacher->user->roles()->syncWithoutDetaching([5]);
            $userTeacher->user->halls()->syncWithoutDetaching([$hall->id]);
        }

        return redirect()->route('Laralum::hall::list')->with('success', 'Provost Assigned Successfully');
    }

    /**
     * @param Hall $hall
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unassign(Hall $hall)
    {
        $this->authorize('ADMIN');

        $userTeacher = UserTeacher::with('user')->whereTeacherId($hall->teacher_id)->first();
        if($userTeacher->user()->exists()){
            $userTeacher->user->halls()->detach($hall->id);
            ($userTeacher->user->halls()->first()) ?: $userTeacher->user->roles()->detach(5);
        }

        $hall->teacher_id = Null;
        $hall->save();
        return redirect()->route('Laralum::hall::list')->with('success', 'Provost Un-Assigned Successfully');
    }

    public function view($slug)
    {
        $data['hall'] = Hall::with(['teacher', 'teacher.department'])->whereSlug($slug)->first();
        return view('frontend.hall.view', $data);
    }
}
