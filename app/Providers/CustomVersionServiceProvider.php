<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as Artisan;
use App\Console\Commands\CustomVersionCommand;
use Illuminate\Foundation\Console\VersionCommand;

class CustomVersionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Override the version command with our custom version command.
        Artisan::starting(function ($artisan) {
            // Unset the default version command
            $artisan->resolveCommands([CustomVersionCommand::class]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Remove the default version command from Artisan
        // Artisan::('version');
    }
}
