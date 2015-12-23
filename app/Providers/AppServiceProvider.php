<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('alpha_spaces', function($attributes, $value, $parameter, $validator) {
            return preg_match('/^[\pL\pM\s]+$/u', $value);
        });
        \Validator::extend('max_length_numeric', function($attributes, $value, $parameter, $validator) {
            return strlen($value) <= $parameter[0];
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
