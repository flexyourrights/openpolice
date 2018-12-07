<?php
namespace OpenPolice;

use Illuminate\Support\ServiceProvider;

class OpenPoliceServiceProvider extends ServiceProvider
{
    
    public function register()
    {
    }

    public function boot()
    {
        require __DIR__ . '/routes.php';
        $this->publishes([
            __DIR__.'/Views'                         => base_path('resources/views/vendor/openpolice'),
            __DIR__.'/Models'                        => base_path('app/Models'),
            __DIR__.'/Models'                        => base_path('app/Models/OpenPolice'),
            __DIR__.'/Public'                        => base_path('public/openpolice'),
            __DIR__.'/Uploads'                       => base_path('storage/app/up/openpolice'),
            __DIR__.'/Database/2018_12_07_OP_create_tables.php'
                => base_path('database/migrations/2018_12_07_OP_create_tables.php'),
            __DIR__.'/Database/OpenPoliceSeeder.php' => base_path('database/seeds/OpenPoliceSeeder.php'),
            __DIR__.'/Database/OpenPoliceDeptSeeder.php' => base_path('database/seeds/OpenPoliceDeptSeeder.php'),
        ]);
    }
    
}