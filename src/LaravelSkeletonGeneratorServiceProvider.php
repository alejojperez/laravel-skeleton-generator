<?php
/**
 * Created by Alejandro Perez on 5/7/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator;

use Illuminate\Support\ServiceProvider;
use AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateSkeletonCommand;

class LaravelSkeletonGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__."/config.php" => config_path('alejojperez-skeleton-generator.php'),
        ], "config");
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GenerateSkeletonCommand::class, function ($app) {

            $configPath = \Config::has('alejojperez-skeleton-generator') ? base_path("config/alejojperez-skeleton-generator.php") : __DIR__."/config.php";

            $config = include $configPath;

            return new GenerateSkeletonCommand($config);
        });
    }
}