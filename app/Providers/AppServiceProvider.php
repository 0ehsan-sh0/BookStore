<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('iran_national_id', function ($attribute, $value, $parameters, $validator) {
            if (strlen($value) != 10 || !is_numeric($value)) {
                return false;
            }
            $check = 0;
            for ($i = 0; $i < 9; $i++) {
                $check += (int)($value[$i]) * (10 - $i);
            }
            $check %= 11;
            $last_digit = (int)($value[9]);
            if ($check < 2 && $check == $last_digit) {
                return true;
            } elseif ($check >= 2 && (11 - $check) == $last_digit) {
                return true;
            }
            return false;
        });
    }
}
