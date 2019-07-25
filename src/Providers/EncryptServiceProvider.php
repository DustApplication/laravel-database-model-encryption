<?php
namespace Dust\Encryption\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;

class EncryptServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * This method is called after all other service providers have
     * been registered, meaning you have access to all other services
     * that have been registered by the framework.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootValidators();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }


    private function bootValidators()
    {

        Validator::extend('unique_encrypted', function ($attribute, $value, $parameters, $validator) {
            /* Parameters 
               parameters[0] = table,
               parameters[1] = field,
               parameters[2] = additional_field_filter/ignore_id, 
               parameters[3] = additional_field_filtervalue,
               parameters[4] = ignore_id 
            */
            // Initialize
            $withFilter = count($parameters) > 3 ? true : false;
            if(!$withFilter){
                $ignore_id = isset($parameters[2]) ? $parameters[2] : '';
            }else{
                $ignore_id = isset($parameters[4]) ? $parameters[4] : '';
            }
            
            // Check using normal checker
            $data = DB::table($parameters[0])->where($parameters[1],$value)->where('id','!=',$ignore_id);
            if($withFilter){
                $data->where($parameters[2],$parameters[3]);
            }
            if($data->get()->first()){
                return false;
            }else{
                // Check if existing on encrypted fields if result is false
                $data = DB::table($parameters[0])->get()->filter(function ($item) use ($value, $parameters, $ignore_id, $withFilter) {
                    $itemValue = isset($item->{$parameters[1]}) ? $item->{$parameters[1]} : '';
                    try {
                        $itemValue = Crypt::decrypt($itemValue);
                    } catch (\Exception $e) {}

                    if($withFilter){
                        return $itemValue == $value && $item->{$parameters[2]} == $parameters[3] && $item->id != $ignore_id;
                    }else{
                        return $itemValue == $value && $item->id != $ignore_id;
                    }
                });
                if($data->first()){
                    return false;
                }
            }

            return true;
        });

        Validator::extend('exists_encrypted', function ($attribute, $value, $parameters, $validator) {
            /* Parameters 
               parameters[0] = table,
               parameters[1] = field,
               parameters[2] = additional_field_filter/ignore_id, 
               parameters[3] = additional_field_filtervalue,
               parameters[4] = ignore_id 
            */

            // Initialize
            $withFilter = count($parameters) > 3 ? true : false;
            if(!$withFilter){
                $ignore_id = isset($parameters[2]) ? $parameters[2] : '';
            }else{
                $ignore_id = isset($parameters[4]) ? $parameters[4] : '';
            }
            
            // Check using normal checker
            $data = DB::table($parameters[0])->where($parameters[1],$value)->where('id','!=',$ignore_id);
            if($withFilter){
                $data->where($parameters[2],$parameters[3]);
            }
            if($data->first()){
                return true;
            }else{
                // Check if existing on encrypted fields
                $data = DB::table($parameters[0])->get()->filter(function ($item) use ($value, $parameters, $ignore_id, $withFilter) {
                            $itemValue = isset($item->{$parameters[1]}) ? $item->{$parameters[1]} : '';
                            try {
                                $itemValue = Crypt::decrypt($itemValue);
                            } catch (\Exception $e) {}

                            if($withFilter){
                                return $itemValue == $value && $item->{$parameters[2]} == $parameters[3] && $item->id != $ignore_id;
                            }else{
                                return $itemValue == $value && $item->id != $ignore_id;
                            }
                        });
                if($data->first()){
                   return true;
                }
            }
            
            return false;
        });
    }
}