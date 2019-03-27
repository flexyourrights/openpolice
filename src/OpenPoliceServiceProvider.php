<?php
namespace OpenPolice;

use Illuminate\Support\ServiceProvider;

class OpenPoliceServiceProvider extends ServiceProvider
{
//    public function register()
//    {
        /*
        * Register the service provider for the dependency.
        */
//        $this->app->register('OpenPolice\OpenPoliceServiceProvider');
        /*
        * Create aliases for the dependency.
        */
//        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
//        $loader->alias('OpenPolice', 'FlexYourRights\OpenPolice\OpenPoliceFacade');
//    }

    public function boot()
    {
        require __DIR__ . '/routes.php';
        $this->publishes([
            __DIR__.'/Views'   => base_path('resources/views/vendor/openpolice'),
            __DIR__.'/Public'  => base_path('public/openpolice'),
            __DIR__.'/Uploads' => base_path('storage/app/up/openpolice'),
            __DIR__.'/Models'  => base_path('storage/app/models/openpolice'),
            __DIR__.'/Models'  => base_path('app/Models/OpenPolice'),
            __DIR__.'/Models'  => base_path('app/Models'),

            __DIR__.'/Database/2018_12_07_OP_create_tables.php'
                => base_path('database/migrations/2018_12_07_OP_create_tables.php'),
            __DIR__.'/Database/OpenPoliceSeeder.php'
                => base_path('database/seeds/OpenPoliceSeeder.php'),
            __DIR__.'/Database/OpenPoliceDeptSeeder.php'
                => base_path('database/seeds/OpenPoliceDeptSeeder.php'),
            base_path('/vendor/flexyourrights/openpolice-departments/src/OpenPoliceDeptSeeder.php')
                => base_path('database/seeds/OpenPoliceDeptSeeder.php')
        ]);
    }
}