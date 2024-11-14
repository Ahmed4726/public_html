<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Http\Request;
use Laralum;
use Storage;
use Response;

class UploadedFileController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['files'] = UploadedFile::allWithOptionalFilter($request->search, $request->from_date, $request->to_date)->appends($request->all());
        return view('laralum.file.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.file.create');
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
        ]);

        $file = new UploadedFile();
        $file->name = $request->name;
        $file->description = $request->description;

        if($request->hasFile('file')){
            $request->file->store('public/image/global');
            $file->path = $request->file->hashName();
        }

        $file->save();
        return redirect()->route('Laralum::file::list')->with('success', 'Uploaded a New File Successfully');
    }

    /**
     * @param UploadedFile $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(UploadedFile $file)
    {
        $this->authorize('ADMIN');
        $file->delete();
        return redirect()->route('Laralum::file::list')->with('success', 'File Deleted Successfully');
    }

    /**
     * @param UploadedFile $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(UploadedFile $file)
    {
        $this->authorize('ADMIN');
        $data['file'] = $file;
        return view('laralum.file.edit', $data);
    }

    /**
     * @param UploadedFile $file
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UploadedFile $file, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $file->name = $request->name;
        $file->description = $request->description;

        if($request->hasFile('file')){
            $request->file->store('public/image/global');
            if($file->path) Storage::delete("public/image/global/$file->path");
            $file->path = $request->file->hashName();
        }

        $file->save();
        return redirect()->route('Laralum::file::list')->with('success', 'Uploaded File Updated Successfully');
    }

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public function fileView(UploadedFile $file)
    {
        return response()->file(public_path("storage/image/global/$file->path"));
    }
}
