<?php
namespace Baldr\APIDiscovery;

use Baldr\APIDiscovery\Http\Controllers\RestDiscoveryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {
    public function boot()
    {
        $this->registerDiscoveryRoute();
    }

    protected function registerDiscoveryRoute(){
        if($this->app->runningInConsole()){
            return $this;
        }

        Route::group(['prefix'=>'_discovery'], function () {
            Route::get('rest', RestDiscoveryController::class);
        });

        return $this;
    }

    public function register()
    {
        //
    }
}