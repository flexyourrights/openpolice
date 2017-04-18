
# FlexYourRights/OpenPolice

[![Laravel](https://img.shields.io/badge/Laravel-5.3-orange.svg?style=flat-square)](http://laravel.com)
[![SurvLoop](https://img.shields.io/badge/SurvLoop-0.0-orange.svg?style=flat-square)](https://github.com/wikiworldorder/survloop)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Open Police is an open-source, open data web app empowering citizens to prepare, file, and track reports of police 
conduct. The site helps users submit complaints or commendations to appropriate police oversight agencies. By allowing 
users to publish reports online, we aim to establish better public transparency and oversight of police activity in the 
U.S.. Open Police is built using <a href="https://github.com/wikiworldorder/survloop" target="_blank">SurvLoop</a>, atop
<a href="https://laravel.com/" target="_blank">Laravel</a>.

<a href="http://openpolicereport.org" target="_blank">OpenPolice.org</a><br />

It is currently in continued, heavy development, with much happening here in early 2017, almost ready to go live. 
I plan to provide more documentation in the coming weeks. Thank you for your interest and patience!

The upcoming Open Police web app can be tested out here, 
feedback welcome via the end of the <b>beta demo</b> submission process:<br />
<a href="http://openpolicereport.org/test" target="_blank">http://openpolicereport.org/test</a><br />
The resulting database designed using the engine, as well as the branching tree which specifies the user's experience: 
<a href="http://openpolicereport.org/db/OP" target="_blank">/db/OP</a><br />
<a href="http://openpolicereport.org/tree/complaint" target="_blank">/tree/complaint</a><br />
Among other methods, the resulting data can also be provided as 
XML included an automatically generated schema, eg.<br />
<a href="http://openpolicereport.org/complaint-xml-schema" target="_blank">/complaint-xml-schema</a><br />
<a href="http://openpolicereport.org/complaint-xml-example" target="_blank">/complaint-xml-example</a><br />
<a href="http://openpolicereport.org/complaint-xml-all" target="_blank">/complaint-xml-all</a>

# Table of Contents
* [Requirements](#requirements)
* [Getting Started](#getting-started)
* [Documentation](#documentation)
* [Roadmap](#roadmap)
* [Change Logs](#change-logs)
* [Contribution Guidelines](#contribution-guidelines)


# <a name="requirements"></a>Requirements

* php: >=5.6.4
* <a href="https://packagist.org/packages/laravel/framework" target="_blank">laravel/framework</a>: 5.3.*
* <a href="https://packagist.org/packages/wikiworldorder/survloop" target="_blank">wikiworldorder/survloop</a>: 0.*

# <a name="getting-started"></a>Getting Started

Here are instructions if you are new to Laravel, or just want step-by-step instructions on how to install its development environment, Homestead: 
<a href="https://OpenPoliceComplaints.org/how-to-install-laravel/" target="_blank">OpenPoliceComplaints.org/how-to-install-laravel/</a>.

The instructions below include the needed steps to install SurvLoop, as well as the Open Police system.

* Install Laravel's default user authentication, one required package, and SurvLoop:

```
$ php artisan make:auth
```

* Update `composer.json` to add requirements and an easier SurvLoop and OpenPolice reference:

```
$ nano composer.json
```

```
...
"require": {
	...
    "wikiworldorder/survloop": "0.*",
    "flexyourrights/openpolice": "0.*",
	...
},
...
"autoload": {
	...
	"psr-4": {
		...
		"SurvLoop\\": "vendor/wikiworldorder/survloop/src/",
		"OpenPolice\\": "vendor/flexyourrights/openpolice/src/",
	}
	...
},
...
```

```
$ composer update
```

* Add the package to your application service providers in `config/app.php`.

```
$ nano config/app.php
```

```php
...
'providers' => [
	...
	SurvLoop\SurvLoopServiceProvider::class,
	OpenPolice\OpenPoliceServiceProvider::class,
	...
],
...
'aliases' => [
	...
	'SurvLoop'	 => 'WikiWorldOrder\SurvLoop\SurvLoopFacade',
	...
],
...
```

* Swap out the SurvLoop user model in `config/auth.php`.

```
$ nano config/auth.php
```

```php
...
'model' => App\Models\User::class,
...
```

* Update composer, publish the package migrations, etc...

```
$ php artisan vendor:publish --force
$ php artisan migrate
$ composer dump-autoload
$ php artisan db:seed --class=SurvLoopSeeder
$ php artisan db:seed --class=OpenPoliceSeeder
$ php artisan db:seed --class=OpenPoliceDepartmentSeeder
```

* Log into Open Police admin dashboard...

```
user: open@openpolice.org
password: openpolice
```


# <a name="documentation"></a>Documentation

Once installed, documentation of this system's database design can be found at /dashboard/db/all . This system's user 
experience design for data entry can be found at /dashboard/tree/map?all=1&alt=1 
or publicly visible links like those above.


# <a name="roadmap"></a>Roadmap

Here's the TODO list for the next release (**1.0**). It's my first time building on Laravel, or GitHub. So sorry.

* [ ] Correct all issues needed for minimum viable product, and launch beta site.
* [ ] Code commenting, learning and adopting more community norms.
* [ ] Finish migrating all raw queries to use Laravel's process.
* [ ] Adding tests.

# <a name="change-logs"></a>Change Logs


# <a name="contribution-guidelines"></a>Contribution Guidelines

Please help educate me on best practices for sharing code in this community.
Please report any issue you find in the issues page.
