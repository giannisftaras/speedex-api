<?php

namespace SpeedexAPI;
use Illuminate\Support\ServiceProvider;

class SpeedexAPIServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('SpeedexAPI', SpeedexAPI::class);
    }
}


?>