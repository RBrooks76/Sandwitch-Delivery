<?php

namespace App\Providers;

use App\HPContactUS;
use App\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Cookie;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view) {

            $user = Auth::user();
            $setting = Setting::first();

            $hp_contact = HPContactUS::first();
            $geturlphoto = $setting->public;

            $path = url('/').$geturlphoto;

            $view
                ->with('get_url_photo', $geturlphoto)
                ->with('path', $path)
                ->with('user', $user)
                ->with('hp_contact', $hp_contact)
                ->with('setting', $setting);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.debug')) {
            error_reporting(E_ALL & ~E_USER_DEPRECATED);
        } else {
            error_reporting(0);
        }
    }
}
