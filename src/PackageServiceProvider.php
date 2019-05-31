<?php

namespace Andrewhlleung\Laraveltools;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    protected $commands = [
            RobotCombineMedia::class
            ,RobotMp3Cut::class
            ,RobotMadeTextOnPng::class
            ,RobotRunAll::class
    ];

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            /// php artisan vendor:publish --tag=andrewhlleung-laraveltools
            $this->publishes([
                    // Assets
                    __DIR__.'/assets/蔣權天下.png' => storage_path('combinemedia/png_sample/蔣權天下.png'),
                    __DIR__.'/assets/順天知命.png' => storage_path('combinemedia/png_sample/順天知命.png'),
                    __DIR__.'/assets/CYanHei-TCHK-Bold-all.ttf' => storage_path('ttf/CYanHei-TCHK-Bold-all.ttf'),
                ], 'andrewhlleung-laraveltools');
        }
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {

            Robot::checkNMake(storage_path('combinemedia/png_sample'));
            Robot::checkNMake(storage_path('ttf'));



            $this->commands($this->commands);
        }
    }
}