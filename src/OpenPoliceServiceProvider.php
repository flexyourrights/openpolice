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
         	 __DIR__.'/Views' 	=> base_path('resources/views/vendor/openpolice'),
         	 __DIR__.'/Public' 	=> base_path('public/openpolice'),
		]);
    }
}