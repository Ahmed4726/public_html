<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Link;
use App\LinkType;
use Illuminate\Http\Request;
use Laralum;

class LinkController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['types'] = LinkType::all();
        $data['links'] = Link::allWithOptionalFilter($request->search, false, $request->type_id, $request->status, false, ['typeInfo', 'department'])->appends($request->all());
        $data['sortable'] = false;
        $filterRequest = array_filter($request->except('page'));
        if(count($filterRequest) == 1 && array_key_exists('type_id', $filterRequest))
            $data['sortable'] = true;

        return view('laralum.link.index', $data);
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentLink($department, Request $request)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
            $data['links'] = Link::allWithOptionalFilter($request->search, $department, $request->type_id, $request->status, false, ['typeInfo', 'department'])->appends($request->all());
        }
        $data['sortable'] = false;
        $data['types'] = LinkType::all();
        $filterRequest = array_filter($request->except('page'));
        if(count($filterRequest) == 1 && array_key_exists('type_id', $filterRequest))
            $data['sortable'] = true;

        return view('laralum.link.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['types'] = LinkType::all();
        return view('laralum.link.create', $data);
    }


    public function departmentLinkCreate($department)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
        }
        $data['types'] = LinkType::all();

        return view('laralum.link.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'label' => 'required',
            'type_id' => 'required',
            'link_url' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $link = new Link();
        $link->label = $request->label;
        $link->type_id = $request->type_id;
        $link->type = LinkType::find($request->type_id, ['name'])->name;
        $link->link_url = $request->link_url;
        $link->target = $request->target;
        $link->css_class = $request->css_class;
        $link->enabled = (isset($request->enabled)) ? 1 : 0;
        $link->department_id = Null;

        if (isset($request->department_id)) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $link->department_id = $request->department_id;
            $uri = 'department';
            $redirectParam['department'] = $request->department_id;
        }else
            $this->authorize('ADMIN');

        $link->save();

        if(isset($uri))
            return redirect()->route("Laralum::$uri::link::list", $redirectParam)->with('success', 'Link Created Successfully');
        else
            return redirect()->route('Laralum::link::list', $redirectParam)->with('success', 'Link Created Successfully');

    }

    /**
     * @param Link $link
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Link $link)
    {
        if($link->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $link->department_id);
        else
            $this->authorize('ADMIN');

        $link->delete();
        if($link->department_id)
            return redirect()->route('Laralum::department::link::list', ['department' => $link->department_id])->with('success', 'Link Deleted Successfully');
        else
            return redirect()->route('Laralum::link::list')->with('success', 'Link Deleted Successfully');
    }

    /**
     * @param Link $link
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Link $link)
    {
        if($link->department_id){
            $this->authorize('DEPARTMENT-SPECIFIC', $link->department_id);
            $data['uriValue'] = $link->department_id;
            $data['uri'] = 'department';
        }else
            $this->authorize('ADMIN');

        $data['link'] = $link;
        $data['types'] = LinkType::all();
        return view('laralum.link.edit', $data);
    }

    /**
     * @param Link $link
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Link $link, Request $request)
    {
        $this->validate($request, [
            'label' => 'required',
            'type_id' => 'required',
            'link_url' => 'required',
        ]);

        $redirectParam['type_id'] = $request->type_id;

        $link->label = $request->label;
        $link->type_id = $request->type_id;
        $link->type = LinkType::find($request->type_id, ['name'])->name;
        $link->link_url = $request->link_url;
        $link->target = $request->target;
        $link->css_class = $request->css_class;
        $link->enabled = (isset($request->enabled)) ? 1 : 0;

        if ($link->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $link->department_id);
            $uri = 'department';
            $redirectParam['department'] = $link->department_id;
        }else
            $this->authorize('ADMIN');

        $link->save();
        if(isset($uri))
            return redirect()->route("Laralum::$uri::link::list", $redirectParam)->with('success', 'Link Updated Successfully');
        else
            return redirect()->route('Laralum::link::list', $redirectParam)->with('success', 'Link Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function typeList()
    {
        $data['types'] = LinkType::all();
        return view('laralum.link.type', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function typeStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type = new LinkType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::link::type::list')->with('success', 'Link Type Created Successfully');
    }

    /**
     * @param LinkType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function typeEdit(LinkType $type)
    {
        $data['type'] = $type;
        return view('laralum.link.type-edit', $data);
    }

    /**
     * @param LinkType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function typeUpdate(LinkType $type, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::link::type::list')->with('success', 'Link Type Updated Successfully');
    }
}
