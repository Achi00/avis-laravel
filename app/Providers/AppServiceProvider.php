<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('jsonUnescaped', function ($data, $status = 200, array $headers = [], $options = 0) {
            return Response::json($data, $status, $headers, JSON_UNESCAPED_UNICODE | $options);
        });
    }
}
