<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class CustomValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('valid_name', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.]+$/u', $value);
        });

        Validator::extend('alphanumericspaces', function($attribute, $value)
        {
            return preg_match('/^[\pL0-9\s.]+$/u', $value);
        });

        Validator::extend('not_exists', function ($attribute, $value, $parameters, $validator) {
            
            if(count($parameters) < 4 && count($parameters)%2 != 0){
                return false;
            }
            
            return !$validator->validateExists($attribute, $value, $parameters);
        });

        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,40}$/";
            return preg_match($regex, $value);
        });

        Validator::extend('latitude', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/";
            return preg_match($regex, $value);
        });

        Validator::extend('longitude', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/";
            return preg_match($regex, $value);
        });


        Validator::extend('event_date', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $success = true;
            try {
                if(isset($data["start_date"]) && !empty($data["start_date"]) && isset($data["end_date"]) && !empty($data["end_date"])){
                    $startDate = Carbon::parse($data["start_date"]);
                    $endDate = Carbon::parse($data["end_date"]);
                    $today = now();
                    if($endDate->gt($startDate) && $startDate->gte($today)){
                        /*if(isset($data["end_time"]) && isset($data["start_time"])){
                            $startTime = Carbon::parse($data["start_date"]." ".$data["start_time"]);
                            $endTime = Carbon::parse($data["end_date"]." ".$data["end_time"]);
                            if($endDate->lte($startDate) && $startTime->gte($today)){
                                $success = false;
                            }
                        }*/
                        $success = true;
                    }else{
                        $success = false;
                    }
                }
            } catch (Exception $e) {
                $success = false;
            }
            
            return $success;
        });

        Validator::extend('limit_file', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $files = $data[$parameters[0]];

            return count($files) <= $parameters[1];
        });
    }
}
