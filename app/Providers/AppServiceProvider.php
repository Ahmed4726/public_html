<?php

namespace App\Providers;

use App\Link;
use App\Menu;
use App\Setting;
use App\Utilitys\Helper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        Paginator::defaultView('pagination::bootstrap-4');

        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }

        $allMenus = Menu::allWithOptionalFilter(false, false, 1, false, 1000);

        foreach ($allMenus->where('type', 'MENU') as $key => $menu) {
            $allMenus[$key]['subMenu'] = $allMenus->where('root_id', $menu->id)->where('type', 'SUB_MENU');
        }

        View::composer(['frontend.layout.header'], function ($view) use ($allMenus) {
            $view->with('menus', $allMenus->where('type', 'MENU'));
        });

        View::composer(['frontend.layout.footer'], function ($view) use ($allMenus) {
            $view->with('links', Link::whereIn('type_id', [5, 2, 4])->whereNull('department_id')->whereEnabled(1)->get());
        });

        View::composer(['frontend.layout.header', 'frontend.layout.footer'], function ($view) {
            $view->with('setting', Setting::first());
        });

        $this->app->singleton('helper', function ($app) {
            return new Helper;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
