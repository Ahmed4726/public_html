<?php

namespace App\Http\Controllers\Laralum;

use App\CustomPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum;
use Storage;

class CustomPageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['pages'] = CustomPage::allWithOptionalFilter($request->search, $request->status);
        return view('laralum.custom-page.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.custom-page.create');
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
            'title' => 'required',
            'slug' => 'required|unique:custom_pages,slug'
        ]);

        $page = new CustomPage();
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->content = $request->description;
        $page->enabled = (isset($request->enabled)) ? 1 : 0;
        $page->save();
        return redirect()->route('Laralum::custom::page::list')->with('success', 'Custom Page Created Successfully');
    }

    /**
     * @param CustomPage $page
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(CustomPage $page)
    {
        $this->authorize('ADMIN');
        $page->delete();
        return redirect()->route('Laralum::custom::page::list')->with('success', 'Custom Page Deleted Successfully');
    }

    /**
     * @param CustomPage $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(CustomPage $page)
    {
        $this->authorize('ADMIN');
        $data['page'] = $page;
        return view('laralum.custom-page.edit', $data);
    }

    /**
     * @param CustomPage $page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CustomPage $page, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'title' => 'required',
            'slug' => [
                'required',
                Rule::unique('custom_pages')->ignore($page->id),
            ]
        ]);

        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->content = $request->description;
        $page->enabled = (isset($request->enabled)) ? 1 : 0;
        $page->save();
        return redirect()->route('Laralum::custom::page::list')->with('success', 'Custom Page Updated Successfully');
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function view($slug)
    {
        $data['page'] = CustomPage::getBySlug($slug);
        return view('frontend.custom-page.view', $data);
    }
}
