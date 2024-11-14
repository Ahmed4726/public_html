<?php

namespace App\Http\Controllers\Laralum;

use App\Center;
use App\CenterFile;
use App\CenterType;
use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\UserTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Laralum;
use Storage;

class CenterController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('CENTER');
        $data['types'] = CenterType::all();
        $data['centers'] = Center::allWithOptionalFilter($request->search, $request->type_id, false, 'typeInfo')->appends($request->all());
        $data['sortable'] = false;
        if (count(array_filter($request->except('page'))) == 1 && key(array_filter($request->except('page'))) == 'type_id') {
            $data['sortable'] = true;
        }

        return view('laralum.center.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = CenterType::all();
        return view('laralum.center.type', $data);
    }

    /**
     * @param CenterType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(CenterType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.center.type-edit', $data);
    }

    /**
     * @param CenterType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(CenterType $type, Request $request)
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
        return redirect()->route('Laralum::center::type::list')->with('success', 'Centers / Offices Type Updated Successfully');
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

        $type = new CenterType();
        $type->name = $request->name;
        $type->max_size = $request->max_size;
        $type->width = $request->width;
        $type->height = $request->height;
        $type->save();
        return redirect()->route('Laralum::center::type::list')->with('success', 'Centers / Offices Type Created Successfully');
    }

    /**
     * @param Center $center
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assign(Center $center)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['center'] = $center;
        return view('laralum.center.assign', $data);
    }

    /**
     * @param Center $center
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignSave(Center $center, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'teacher_id' => 'required'
        ]);
        $center->teacher_id = $request->teacher_id;
        $center->save();

        $userTeacher = UserTeacher::with('user')->whereTeacherId($center->teacher_id)->first();
        if ($userTeacher->user()->exists()) {
            $userTeacher->user->roles()->syncWithoutDetaching([7]);
            $userTeacher->user->centers()->syncWithoutDetaching([$center->id]);
        }

        return redirect()->route('Laralum::center::list')->with('success', 'Centers / Offices Director Assigned Successfully');
    }

    /**
     * @param Center $center
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unassign(Center $center)
    {
        $this->authorize('ADMIN');

        $userTeacher = UserTeacher::with('user')->whereTeacherId($center->teacher_id)->first();
        if ($userTeacher->user()->exists()) {
            $userTeacher->user->centers()->detach($center->id);
            ($userTeacher->user->centers()->first()) ?: $userTeacher->user->roles()->detach(7);
        }

        $center->teacher_id = null;
        $center->save();
        return redirect()->route('Laralum::center::list')->with('success', 'Centers / Offices Director Un-Assigned Successfully');
    }

    /**
     * @param Center $center
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Center $center)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $data['center'] = $center;
        $data['types'] = CenterType::all();
        return view('laralum.center.edit', $data);
    }

    public function create()
    {
        $this->authorize('ADMIN');
        $data['types'] = CenterType::all();
        return view('laralum.center.create', $data);
    }

    /**
     * @param Center $center
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Center $center, Request $request)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);

        if (Auth::user()->can('ADMIN')) {
            $this->validate($request, [
                'name' => 'required',
                'type_id' => 'required',
                'slug' => [
                    'required',
                    Rule::unique('centers')->ignore($center->id),
                ]
            ]);

            $center->name = $request->name;
            $center->type_id = $request->type_id;
            $center->type = CenterType::find($request->type_id, ['name'])->name;
            ;
            $center->slug = $request->slug;
        }

        $center->name_ben = $request->name_ben;
        $center->website_link = $request->website_link;
        $center->description = $request->description;
        $center->director_label = $request->director_label;
        $center->director_msg_label = $request->director_msg_label;
        $center->message_from_director = $request->message_from_director;
        $center->ex_directors = $request->ex_directors;

        if ($request->config) {
            try {
                $center->config = \Laralum::configTextToArray($request->config);
            } catch (\Throwable $exception) {
                echo $exception->getMessage();
            }
        }

        $center->director_name = $request->director_name;

        ($center->director_image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = 500;
            $height = 200;
            $width = 200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/center/$fileName", $width, $height);
            if ($center->director_image_url) {
                Storage::delete("public/image/center/$center->director_image_url");
            }
            $center->director_image_url = $fileName;
        }

        $center->save();
        return redirect()->route('Laralum::center::list', ['type_id' => $request->type_id])->with('success', 'Centers / Offices Updated Successfully');
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
            'type_id' => 'required',
            'slug' => 'required|unique:centers,slug'
        ]);

        $center = new Center();
        $center->name = $request->name;
        $center->name_ben = $request->name_ben;
        $center->type_id = $request->type_id;
        $center->type = CenterType::find($request->type_id, ['name'])->name;
        ;
        $center->slug = $request->slug;
        $center->website_link = $request->website_link;
        $center->description = $request->description;
        $center->director_label = $request->director_label;
        $center->director_msg_label = $request->director_msg_label;
        $center->message_from_director = $request->message_from_director;
        $center->ex_directors = $request->ex_directors;

        try {
            $center->config = \Laralum::configTextToArray($request->config);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $center->director_name = $request->director_name;

        if ($request->hasFile('image')) {
            $maxSize = 500;
            $height = 200;
            $width = 200;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/center/$fileName", $width, $height);
            $center->director_image_url = $fileName;
        }

        $center->save();
        return redirect()->route('Laralum::center::list', ['type_id' => $request->type_id])->with('success', 'Centers / Offices Created Successfully');
    }

    /**
     * @param Center $center
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadList(Center $center, Request $request)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $data['center'] = $center;
        $data['uploads'] = CenterFile::allWithOptionalFilter($request->search, false, $center->id, false, 'center');
        return view('laralum.center.upload.index', $data);
    }

    /**
     * @param Center $center
     * @param CenterFile $upload
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadDelete(Center $center, CenterFile $upload)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $upload->delete();
        return redirect()->route('Laralum::center::upload::list', ['center' => $center->id])->with('success', 'Upload file deleted successfully');
    }

    /**
     * @param Center $center
     * @param CenterFile $upload
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadEdit(Center $center, CenterFile $upload)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $data['upload'] = $upload;
        $data['center'] = $center;
        return view('laralum.center.upload.edit', $data);
    }

    /**
     * @param Center $center
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadCreate(Center $center)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $data['center'] = $center;
        return view('laralum.center.upload.create', $data);
    }

    /**
     * @param Center $center
     * @param CenterFile $upload
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadUpdate(Center $center, CenterFile $upload, Request $request)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $this->validate($request, [
            'name' => 'required',
        ]);

        $upload->name = $request->name;
        $upload->note = $request->note;
        $upload->external_link = $request->external_link;
        $upload->listing_enabled = (isset($request->listing_enabled)) ? 1 : 0;

        if ($request->hasFile('file')) {
            $request->file->store("public/image/center/$center->id");
            if ($upload->path) {
                Storage::delete("public/image/center/$center->id/$upload->path");
            }
            $upload->path = $request->file->hashName();
        }

        $center->files()->save($upload);
        return redirect()->route('Laralum::center::upload::list', ['center' => $center->id])->with('success', 'Center / Office File Updated Successfully');
    }

    /**
     * @param Center $center
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadStore(Center $center, Request $request)
    {
        $this->authorize('CENTER-SPECIFIC', $center->id);
        $this->validate($request, [
            'name' => 'required',
            'file' => 'required'
        ]);

        $file = new CenterFile();
        $file->name = $request->name;
        $file->note = $request->note;
        $file->external_link = $request->external_link;
        $file->listing_enabled = (isset($request->listing_enabled)) ? 1 : 0;

        if ($request->hasFile('file')) {
            $request->file->store("public/image/center/$center->id");
            $file->path = $request->file->hashName();
        }

        $center->files()->save($file);
        return redirect()->route('Laralum::center::upload::list', ['center' => $center->id])->with('success', 'Center / Office File Saved Successfully');
    }

    /**
     * @param $center
     * @param CenterFile $file
     * @return mixed
     */
    public function fileView($center, CenterFile $file)
    {
        return \Storage::response("public/image/center/$center/$file->path");
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($slug)
    {
        $data['center'] = Center::getDetailsBySlug($slug);
        return view('frontend.center.view', $data);
    }

    /**
     * @param Center $center
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advanceOptionList(Center $center)
    {
        $data['center'] = $center;
        return view('laralum.center.advance-option', $data);
    }
}
