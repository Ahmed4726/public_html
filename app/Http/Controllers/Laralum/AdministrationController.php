<?php

namespace App\Http\Controllers\Laralum;

use App\AdministrativeMember;
use App\AdministrativeMemberRole;
use App\AdministrativeRole;
use App\AdministrativeRoleType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum;
use Storage;

class AdministrationController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('ADMIN');
        $data['members'] = AdministrativeMember::allWithOptionalFilter();
        return view('laralum.administration.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.administration.create');
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

        $member = new AdministrativeMember();
        $member->name = $request->name;
        $member->designation = $request->designation;
        $member->department = $request->department;
        $member->address = $request->address;
        $member->primary_email = $request->primary_email;
        $member->other_emails = $request->other_emails;
        $member->website_link = $request->website_link;
        $member->fax = $request->fax;
        $member->fabx = $request->fabx;
        $member->message = $request->message;
        $member->biography = $request->biography;

        if ($request->hasFile('image')) {
            $maxSize = 500;
            $height = 255;
            $width = 236;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/administration/$fileName", $width, $height);
            $member->image_url = $fileName;
        }

        $member->save();
        return redirect()->route('Laralum::administration::list')->with('success', 'Administrative Member Created Successfully');
    }

    /**
     * @param AdministrativeMember $member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(AdministrativeMember $member)
    {
        $this->authorize('ADMIN');
        $data['member'] = $member;
        return view('laralum.administration.edit', $data);
    }

    /**
     * @param AdministrativeMember $member
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AdministrativeMember $member, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $member->name = $request->name;
        $member->designation = $request->designation;
        $member->department = $request->department;
        $member->address = $request->address;
        $member->primary_email = $request->primary_email;
        $member->other_emails = $request->other_emails;
        $member->website_link = $request->website_link;
        $member->fax = $request->fax;
        $member->fabx = $request->fabx;
        $member->message = $request->message;
        $member->biography = $request->biography;

        ($member->image_url == $request->path) ? $imageUploadRequired = false : $imageUploadRequired = true;
        if ($request->hasFile('image') && $imageUploadRequired) {
            $maxSize = 500;
            $height = 255;
            $width = 236;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/administration/$fileName", $width, $height);
            if ($member->image_url) {
                Storage::delete("public/image/administration/$member->image_url");
            }
            $member->image_url = $fileName;
        }

        $member->save();
        return redirect()->route('Laralum::administration::list')->with('success', 'Administrative Member Updated Successfully');
    }

    public function delete(AdministrativeMember $member)
    {
        $this->authorize('ADMIN');
        $member->roles()->detach();
        $member->delete();
        return redirect()->route('Laralum::administration::list')->with('success', 'Administration Member Deleted Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleList()
    {
        $this->authorize('ADMIN');
        $data['roles'] = AdministrativeRole::allWithOptionalFilter();
        $data['sortable'] = true;
        return view('laralum.administration.role.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleCreate()
    {
        $this->authorize('ADMIN');
        $data['types'] = AdministrativeRoleType::all();
        return view('laralum.administration.role.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
            'preview' => 'required',
        ]);

        $role = new AdministrativeRole();
        $role->name = $request->name;
        $role->type_id = $request->type_id;
        $role->type = AdministrativeRoleType::find($request->type_id, ['name'])->name;
        $role->description = $request->description;
        $role->preview = $request->preview;
        $role->page_url = $request->page_url;
        $role->save();
        return redirect()->route('Laralum::administration::role::list')->with('success', 'Administrative Role Created Successfully');
    }

    /**
     * @param AdministrativeRole $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleEdit(AdministrativeRole $role)
    {
        $this->authorize('ADMIN');
        $data['types'] = AdministrativeRoleType::all();
        $data['role'] = $role;
        return view('laralum.administration.role.edit', $data);
    }

    /**
     * @param AdministrativeRole $role
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleUpdate(AdministrativeRole $role, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
            'preview' => 'required',
        ]);

        $role->name = $request->name;
        $role->type_id = $request->type_id;
        $role->type = AdministrativeRoleType::find($request->type_id, ['name'])->name;
        $role->description = $request->description;
        $role->preview = $request->preview;
        $role->page_url = $request->page_url;
        $role->save();
        return redirect()->route('Laralum::administration::role::list')->with('success', 'Administrative Role Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleTypeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = AdministrativeRoleType::all();
        return view('laralum.administration.role.type.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleTypeStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type = new AdministrativeRoleType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::administration::role::type::list')->with('success', 'Administrative Role Type Created Successfully');
    }

    /**
     * @param AdministrativeRoleType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleTypeEdit(AdministrativeRoleType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.administration.role.type.edit', $data);
    }

    /**
     * @param AdministrativeRoleType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleTypeUpdate(AdministrativeRoleType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::administration::role::type::list')->with('success', 'Administrative Role Type Updated Successfully');
    }

    /**
     * @param AdministrativeMember $member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignRole(AdministrativeMember $member)
    {
        $this->authorize('ADMIN');
        $data['roles'] = AdministrativeRole::allWithOptionalFilter();
        $data['member'] = $member;
        return view('laralum.administration.role.assign', $data);
    }

    /**
     * @param AdministrativeMember $member
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function assignRoleStore(AdministrativeMember $member, Request $request)
    {
        $this->authorize('ADMIN');
        $roles = array_keys($request->except('_token'));
        AdministrativeMemberRole::roleModify($member->id, $roles);
        $data['roles'] = AdministrativeRole::allWithOptionalFilter();
        $data['member'] = $member;
        return redirect()->route('Laralum::administration::list')->with('success', 'Administrative Member Role Assigned Successfully');
    }

    public function administrationView(Request $request)
    {
        if (!$request->has('roleType')) {
            return redirect()->route('Frontend::not-found');
        }

        if ($request->roleType == 'PRO_VICE_CHANCELLOR') {
            $data['members'] = AdministrativeRole::whereType($request->roleType)->whereName($request->roleName)->first()->members;
            $data['rollName'] = $request->roleName;
            return view('frontend.admin.pro-vice-chancellor', $data);
        } else {
            $data['member'] = AdministrativeRole::whereType($request->roleType)->whereName($request->roleName)->first()->members->first();
            $data['rollName'] = $request->roleName;
            return view('frontend.admin.member-view', $data);
        }
    }

    public function profileView(AdministrativeMember $admin)
    {
        dd('oks');
        $data['member'] = $admin;
        $data['rollName'] = '';
        return view('frontend.admin.member-view', $data);
    }
}
