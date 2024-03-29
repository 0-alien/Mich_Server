<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
      Validator::extend('not_exists', function ($attribute, $value, $parameters, $validator) {
        return !DB::table($parameters[0])->where($parameters[1], $value)->exists();
      });

      Validator::extend('alpha_spaces', function ($attribute, $value, $parameters, $validator) {
        return preg_match('/^[\pL\s]+$/u', $value);
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
