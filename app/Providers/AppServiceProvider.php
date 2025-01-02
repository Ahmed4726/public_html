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
        // Force HTTPS in production environment
        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }
    
        // Check if the environment is production
        if (env('APP_ENV') === 'production') {
            // Fetch menus only in production environment
            try {
                $allMenus = Menu::allWithOptionalFilter(false, false, 1, false, 1000);
    
                // Populate sub-menu data
                foreach ($allMenus->where('type', 'MENU') as $key => $menu) {
                    $allMenus[$key]['subMenu'] = $allMenus->where('root_id', $menu->id)->where('type', 'SUB_MENU');
                }
    
                // Share menus with the frontend header view
                View::composer(['frontend.layout.header'], function ($view) use ($allMenus) {
                    $view->with('menus', $allMenus->where('type', 'MENU'));
                });
    
                // Share footer links
                View::composer(['frontend.layout.footer'], function ($view) use ($allMenus) {
                    $view->with('links', Link::whereIn('type_id', [5, 2, 4])->whereNull('department_id')->whereEnabled(1)->get());
                });
    
                // Share site settings with both header and footer views
                View::composer(['frontend.layout.header', 'frontend.layout.footer'], function ($view) {
                    $view->with('setting', Setting::first());
                });
            } catch (\Exception $e) {
                // Log error in case of failure
                \Log::error('Error fetching menus: ' . $e->getMessage());
            }
        }
    
        // Register a helper class in the container
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
