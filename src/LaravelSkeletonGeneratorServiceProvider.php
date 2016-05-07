<?php
/**
 * Created by Alejandro Perez on 5/7/16
 * github page: https://github.com/alejojperez
 */

namespace LaravelSkeletonGenerator;

use LaravelSkeletonGenerator\Commands\Generate\GenerateSkeletonCommand;

class LaravelSkeletonGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GenerateSkeletonCommand::class, function ($app) {

            $configPath = \Config::has('laravel-skeleton-generator') ? base_path("config/laravel-skeleton-generator.php") : __DIR__."/config.php";

            $config = include $configPath;

            return new GenerateSkeletonCommand($config);
        });
    }
}