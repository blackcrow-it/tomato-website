<?php

namespace App\Providers;

use App\Setting;
use Config;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Log;
use Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!Str::startsWith(request()->getRequestUri(), '/admin')) {
            Paginator::defaultView('frontend.paginate');
        }

        try {
            $settings = Setting::all();
            foreach ($settings as $item) {
                Config::set('settings.' . $item->key, $item->value);
            }
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}
