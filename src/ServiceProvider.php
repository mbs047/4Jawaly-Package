<?php

namespace Devhereco\ForJawaly;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/configs/forjawaly.php', 'forjawaly');

        $this->publishes([
            __DIR__.'/configs/forjawaly.php' => config_path('forjawaly.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(ForJawaly::class, function () {
            return new ForJawaly();
        });
    }

}
