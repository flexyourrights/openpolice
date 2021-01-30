<?php
/**
  * OpenPoliceServiceProvider in Laravel extension
  * which copies files where they are needed after installation.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since  v0.3.0
  */
namespace FlexYourRights\OpenPolice;

use Illuminate\Support\ServiceProvider;

class OpenPoliceServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind('openpolice', function($app) {
            return new OpenPoliceFacade();
        });
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        //$this->loadViewsFrom(__DIR__ . '/Views', 'openpolice');
        $dbMig = '2021_01_28_000000_create_openpolice_tables';
        $this->publishes([
            __DIR__.'/Views'   => base_path('resources/views/vendor/openpolice'),
            __DIR__.'/Public'  => base_path('public/openpolice'),
            __DIR__.'/Uploads' => base_path('storage/app/up/openpolice'),
            __DIR__.'/Models'  => base_path('app/Models'),

            __DIR__.'/Database/' . $dbMig . '.php'
                => base_path('database/migrations/' . $dbMig . '.php'),
            __DIR__.'/Database/OpenPoliceSeeder.php'
                => base_path('database/seeders/OpenPoliceSeeder.php'),
            base_path('/vendor/flexyourrights/openpolice-departments/src/OpenPoliceDeptSeeder.php')
                => base_path('database/seeders/OpenPoliceDeptSeeder.php'),

            base_path('/vendor/flexyourrights/openpolice-website/src')
                => base_path('storage/app/up/openpolice')
        ]);
    }
}