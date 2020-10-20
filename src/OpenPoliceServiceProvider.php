<?php
namespace FlexYourRights\OpenPolice;

use Illuminate\Support\ServiceProvider;

class OpenPoliceServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind('survlooporg', function($app) {
            return new SurvloopOrgFacade();
        });
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        //$this->loadViewsFrom(__DIR__ . '/Views', 'openpolice');
        $dbMig = '2019_11_01_000000_create_openpolice_tables';
        $this->publishes([
            __DIR__.'/Views'   => base_path('resources/views/vendor/openpolice'),
            __DIR__.'/Public'  => base_path('public/openpolice'),
            __DIR__.'/Uploads' => base_path('storage/app/up/openpolice'),
            __DIR__.'/Models'  => base_path('app/Models'),

            //base_path('/vendor/flexyourrights/openpolice-website/src')
            //    => base_path('storage/app/up/openpolice-website'),

            __DIR__.'/Database/' . $dbMig . '.php'
                => base_path('database/migrations/' . $dbMig . '.php'),
            __DIR__.'/Database/OpenPoliceSeeder.php'
                => base_path('database/seeders/OpenPoliceSeeder.php'),
            __DIR__.'/Database/OpenPoliceSLSeeder.php'
                => base_path('database/seeders/OpenPoliceSLSeeder.php'),
            base_path('/vendor/flexyourrights/openpolice-departments/src/OpenPoliceDeptSeeder.php')
                => base_path('database/seeders/OpenPoliceDeptSeeder.php')
        ]);
    }
}