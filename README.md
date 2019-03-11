
# FlexYourRights/OpenPolice

[![Laravel](https://img.shields.io/badge/Laravel-5.8-orange.svg?style=flat-square)](http://laravel.com)
[![SurvLoop](https://img.shields.io/badge/SurvLoop-0.1-orange.svg?style=flat-square)](https://github.com/wikiworldorder/survloop)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

Open Police is an open-source, open data web app empowering citizens to prepare, file, and track reports of police 
conduct. The site helps users submit complaints or commendations to appropriate police oversight agencies. By allowing 
users to publish reports online, we aim to establish better public transparency and oversight of police activity in the 
U.S.. Open Police is built using <a href="https://github.com/wikiworldorder/survloop" target="_blank">SurvLoop</a>, atop
<a href="https://laravel.com/" target="_blank">Laravel</a>.

<a href="https://openpolice.org" target="_blank">OpenPolice.org</a><br />

It is currently in continued, heavy development, with the pilot program rolling now in early 2019. 
I plan to provide more documentation in the coming weeks. Thank you for your interest and patience!

The upcoming Open Police web app can be tested out here, 
feedback welcome via the end of the <b>beta demo</b> submission process:<br />
<a href="https://openpolice.org/filing-your-police-complaint" target="_blank">/filing-your-police-complaint</a><br />
The resulting database designed using the engine, as well as the branching tree which specifies the user's experience: 
<a href="https://openpolice.org/db/OP" target="_blank">/db/OP</a><br />
<a href="https://openpolice.org/tree/complaint" target="_blank">/tree/complaint</a><br />
Among other methods, the resulting data can also be provided as 
XML included an automatically generated schema, eg.<br />
<a href="https://openpolice.org/complaint-xml-schema" target="_blank">/complaint-xml-schema</a><br />
<a href="https://openpolice.org/complaint-xml-example" target="_blank">/complaint-xml-example</a><br />
<a href="https://openpolice.org/complaint-xml-all" target="_blank">/complaint-xml-all</a>

# Table of Contents
* [Requirements](#requirements)
* [Getting Started](#getting-started)
* [Documentation](#documentation)
* [Roadmap](#roadmap)
* [Change Logs](#change-logs)
* [Contribution Guidelines](#contribution-guidelines)
* [Reporting a Security Vulnerability](#security-help)


# <a name="requirements"></a>Requirements

* php: >=7.3
* <a href="https://packagist.org/packages/laravel/laravel" target="_blank">laravel/laravel</a>: 5.8.*
* <a href="https://packagist.org/packages/wikiworldorder/survloop" target="_blank">wikiworldorder/survloop</a>: 0.1.*

# <a name="getting-started"></a>Getting Started

The instructions below include the needed steps to install Laravel, SurvLoop, as well as the Open Police system.
For more on creating environments to host Laravel, you can find more instructions on
<a href="https://survloop.org/how-to-install-laravel-on-a-digital-ocean-server" target="_blank">SurvLoop.org</a>.

* Use Composer to install Laravel with default user authentication, one required package:

```
$ composer global require "laravel/installer"
$ composer create-project laravel/laravel OpenPolice "5.8.*"
$ cd OpenPolice
$ php artisan make:auth
$ php artisan vendor:publish --tag=laravel-notifications
```

* Update `composer.json` to add requirements and an easier SurvLoop and OpenPolice reference:

```
$ nano composer.json
```

```
...
"require": {
	...
    "flexyourrights/openpolice": "^0.1.*",
	...
},
...
"autoload": {
	...
	"psr-4": {
		...
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
    'name' => 'Open Police Complaints',
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
	'OpenPolice' => 'FlexYourRights\OpenPolice\OpenPoliceFacade',
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

* For now, to apply database design changes to the same installation you are working in, depending on your server, 
you might also need something like this...

```
$ chown -R www-data:33 app/Models
$ chown -R www-data:33 database
```

* Browse to load the style sheets, etc.. /dashboard/css-reload

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

# <a name="security-help"></a>Reporting a Security Vulnerability

We want to ensure that Open Police Complaints is a secure HTTP open data platform for everyone. 
If you've discovered a security vulnerability in OpenPolice.org, 
we appreciate your help in disclosing it to us in a responsible manner.

Publicly disclosing a vulnerability can put the entire community at risk. 
If you've discovered a security concern, please email us at wikiworldorder *at* protonmail.com. 
We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. 
We consider correspondence sent to wikiworldorder *at* protonmail.com our highest priority, 
and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a release will be deployed as soon as possible.
