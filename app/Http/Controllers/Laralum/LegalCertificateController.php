<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\LegalCertificate;
use App\LegalCertificateType;
use Illuminate\Http\Request;
use Storage;

class LegalCertificateController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('NOC');
        $data['types'] = LegalCertificateType::all();
        $data['certificates'] = LegalCertificate::allWithOptionalFilter($request->search, $request->type_id, $request->from_date, $request->to_date, false, 'typeInfo')->appends($request->all());
        return view('laralum.certificate.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('NOC');
        $data['types'] = LegalCertificateType::all();
        return view('laralum.certificate.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('NOC');
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $certificate = new LegalCertificate();
        $certificate->name = $request->name;
        $certificate->type_id = $request->type_id;
        $certificate->type = LegalCertificateType::find($request->type_id, ['name'])->name;
        $certificate->designation = $request->designation;
        $certificate->address = $request->address;
        if ($request->date) {
            $certificate->date = date('Y-m-d', strtotime($request->date));
        }
        $certificate->external_link = $request->external_link;

        if ($request->hasFile('file')) {
            $request->file->store('public/image/certificate');
            $certificate->path = $request->file->hashName();
        }

        $certificate->save();
        return redirect()->route('Laralum::certificate::list', $redirectParam)->with('success', 'NOC & GO Created Successfully');
    }

    /**
     * @param LegalCertificate $certificate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(LegalCertificate $certificate)
    {
        $this->authorize('NOC');
        $certificate->delete();
        return redirect()->route('Laralum::certificate::list')->with('success', 'NOC/GO Deleted Successfully');
    }

    public function bulkDelete(Request $request)
    {
        // Authorize the action for bulk deletion
        $this->authorize('NOC');

        // Retrieve the selected certificate IDs from the request
        $certificateIds = $request->input('selected_certificates');

        // Check if any IDs are provided
        if (!empty($certificateIds)) {
            // Perform the bulk deletion
            LegalCertificate::whereIn('id', $certificateIds)->delete();

            // Redirect back with a success message
            return redirect()->route('Laralum::certificate::list')->with('success', 'Selected NOC/GO(s) Deleted Successfully');
        }

        // Redirect back with an error message if no IDs were selected
        return redirect()->route('Laralum::certificate::list')->with('error', 'No NOC/GO(s) were selected for deletion');
    }


    /**
     * @param LegalCertificate $certificate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(LegalCertificate $certificate)
    {
        $this->authorize('NOC');
        $data['certificate'] = $certificate;
        $data['types'] = LegalCertificateType::all();
        return view('laralum.certificate.edit', $data);
    }

    /**
     * @param LegalCertificate $certificate
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(LegalCertificate $certificate, Request $request)
    {
        $this->authorize('NOC');
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $certificate->name = $request->name;
        $certificate->type_id = $request->type_id;
        $certificate->type = LegalCertificateType::find($request->type_id, ['name'])->name;
        $certificate->designation = $request->designation;
        $certificate->address = $request->address;
        if ($request->date) {
            $certificate->date = date('Y-m-d', strtotime($request->date));
        }
        $certificate->external_link = $request->external_link;

        if ($request->hasFile('file')) {
            $request->file->store('public/image/certificate');
            if ($certificate->path) {
                Storage::delete("public/image/certificate/$certificate->path");
            }
            $certificate->path = $request->file->hashName();
        }

        $certificate->save();
        return redirect()->route('Laralum::certificate::list', $redirectParam)->with('success', 'NOC & GO Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = LegalCertificateType::all();
        return view('laralum.certificate.type', $data);
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

        $type = new LegalCertificateType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::certificate::type::list')->with('success', 'NOC & GO Type Created Successfully');
    }

    /**
     * @param LegalCertificateType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(LegalCertificateType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.certificate.type-edit', $data);
    }

    /**
     * @param LegalCertificateType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(LegalCertificateType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::certificate::type::list')->with('success', 'NOC & GO Type Updated Successfully');
    }

    public function typeDelete(LegalCertificateType $type)
    {
        $this->authorize('ADMIN');
        $type->delete();
        return redirect()->route('Laralum::certificate::type::list')->with('success', 'Certificate Type Deleted Successfully');
    }

    /**
     * @param LegalCertificate $certificate
     * @return mixed
     */
    public function fileView(LegalCertificate $certificate)
    {
        return response()->file(public_path("storage/image/certificate/$certificate->path"));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        return view('frontend.certificate.list');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(Request $request)
    {
        return LegalCertificate::allWithOptionalFilter($request->search, false, $request->fromDate, $request->toDate, false, $request->relation);
    }
}
