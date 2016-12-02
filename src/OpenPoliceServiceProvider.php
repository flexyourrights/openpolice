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
        $migFile = '2016_11_30_221418_OP_create_tables.php';
        $this->publishes([
         	 __DIR__.'/Views' 		=> base_path('resources/views/vendor/openpolice'),
         	 __DIR__.'/Public' 		=> base_path('public/openpolice'),
         	 __DIR__.'/Models' 		=> base_path('app/Models'),
         	 __DIR__.'/Database/' . $migFile => base_path('database/migrations/' . $migFile),
         	 __DIR__.'/Database/OpenPoliceSeeder.php' => base_path('database/seeds/OpenPoliceSeeder.php'),
         	 __DIR__.'/Database/OpenPoliceDepartmentSeeder.php' => base_path('database/seeds/OpenPoliceDepartmentSeeder.php'),
		]);
    }
    
}