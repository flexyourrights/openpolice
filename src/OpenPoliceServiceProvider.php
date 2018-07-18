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
        $migFile = '2018_04_13_OP_create_tables';
        $this->publishes([
            __DIR__.'/Views'                         => base_path('resources/views/vendor/openpolice'),
            __DIR__.'/Public'                        => base_path('public/openpolice'),
            __DIR__.'/Models'                        => base_path('app/Models'),
            __DIR__.'/Uploads'                       => base_path('storage/app/up/openpolice'),
            __DIR__.'/Database/' . $migFile          => base_path('database/migrations/' . $migFile),
            __DIR__.'/Database/OpenPoliceSeeder.php' => base_path('database/seeds/OpenPoliceSeeder.php'),
            __DIR__.'/Database/OpenPoliceDepartmentSeeder.php' 
                => base_path('database/seeds/OpenPoliceDepartmentSeeder.php'),
        ]);
    }
    
}