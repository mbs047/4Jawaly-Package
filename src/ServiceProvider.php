<?php

namespace Devhereco\4Jawaly;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/configs/4jawaly.php', '4jawaly');

        $this->publishes([
            __DIR__.'/configs/4jawaly.php' => config_path('4jawaly.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(4Jawaly::class, function () {
            return new 4Jawaly();
        });
    }

}
