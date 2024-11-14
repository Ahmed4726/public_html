<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['menus'] = Menu::allWithOptionalFilter($request->search, 'MENU', $request->status)->appends($request->all());
        (count(array_filter($request->except('page'))) == 0) ? $data['sortable'] = true : $data['sortable'] = false;
        return view('laralum.menu.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        return view('laralum.menu.create');
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
            'display_text' => 'required',
            'link' => 'required',
        ]);

        $menu = new Menu();
        $menu->display_text = $request->display_text;
        $menu->link = $request->link;
        $menu->type = 'MENU';
        $menu->enabled = (isset($request->enabled)) ? 1 : 0;
        $menu->animation_enabled = (isset($request->animation_enabled)) ? 1 : 0;
        $menu->save();
        return redirect()->route('Laralum::menu::list')->with('success', 'Menu Created Successfully');
    }

    /**
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Menu $menu)
    {
        $this->authorize('ADMIN');
        $menu->delete();
        return redirect()->route('Laralum::menu::list')->with('success', 'Menu Deleted Successfully');
    }

    /**
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Menu $menu)
    {
        $this->authorize('ADMIN');
        $data['menu'] = $menu;
        return view('laralum.menu.edit', $data);
    }

    /**
     * @param Menu $menu
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Menu $menu, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'display_text' => 'required',
            'link' => 'required',
        ]);

        $menu->display_text = $request->display_text;
        $menu->link = $request->link;
        $menu->enabled = (isset($request->enabled)) ? 1 : 0;
        $menu->animation_enabled = (isset($request->animation_enabled)) ? 1 : 0;
        $menu->save();
        return redirect()->route('Laralum::menu::list')->with('success', 'Menu Updated Successfully');
    }


    /**
     * @param Menu $menu
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuList(Menu $menu, Request $request)
    {
        $this->authorize('ADMIN');
        $data['menu'] = $menu;
        $data['subMenus'] = Menu::allWithOptionalFilter($request->search, 'SUB_MENU', $request->status, $menu->id)->appends($request->all());
        (count(array_filter($request->except('page'))) == 0) ? $data['sortable'] = true : $data['sortable'] = false;
        return view('laralum.menu.sub-menu.index', $data);
    }

    /**
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuCreate(Menu $menu)
    {
        $this->authorize('ADMIN');
        $data['menu'] = $menu;
        return view('laralum.menu.sub-menu.create', $data);
    }

    /**
     * @param $menu
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuStore($menu, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'display_text' => 'required',
            'link' => 'required',
        ]);

        $subMenu = new Menu();
        $subMenu->display_text = $request->display_text;
        $subMenu->link = $request->link;
        $subMenu->type = 'SUB_MENU';
        $subMenu->root_id = $menu;
        $subMenu->enabled = (isset($request->enabled)) ? 1 : 0;
        $subMenu->animation_enabled = (isset($request->animation_enabled)) ? 1 : 0;
        $subMenu->save();
        return redirect()->route('Laralum::menu::submenu::list', ['menu' => $menu])->with('success', 'Sub Menu Created Successfully');
    }

    /**
     * @param Menu $menu
     * @param Menu $submenu
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuDelete(Menu $menu, Menu $submenu)
    {
        $this->authorize('ADMIN');
        $submenu->delete();
        return redirect()->route('Laralum::menu::submenu::list', ['menu' => $menu->id])->with('success', 'Sub Menu Deleted Successfully');
    }

    /**
     * @param Menu $menu
     * @param Menu $submenu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuEdit(Menu $menu, Menu $submenu)
    {
        $this->authorize('ADMIN');
        $data['menu'] = $menu;
        $data['subMenu'] = $submenu;
        return view('laralum.menu.sub-menu.edit', $data);
    }

    /**
     * @param $menu
     * @param Menu $submenu
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function subMenuUpdate($menu, Menu $submenu, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'display_text' => 'required',
            'link' => 'required',
        ]);

        $submenu->display_text = $request->display_text;
        $submenu->link = $request->link;
        $submenu->enabled = (isset($request->enabled)) ? 1 : 0;
        $submenu->animation_enabled = (isset($request->animation_enabled)) ? 1 : 0;
        $submenu->save();
        return redirect()->route('Laralum::menu::submenu::list', ['menu' => $menu])->with('success', 'Sub Menu Updated Successfully');
    }
}
