
# FlexYourRights/OpenPolice

[![Laravel](https://img.shields.io/badge/Laravel-8.2-orange.svg?style=flat-square)](http://laravel.com)
[![Survloop](https://img.shields.io/badge/Survloop-0.2-orange.svg?style=flat-square)](https://github.com/rockhopsoft/survloop)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

# Table of Contents
* [About](#about)
* [Requirements](#requirements)
* [Getting Started](#getting-started)
* [Documentation](#documentation)
* [Roadmap](#roadmap)
* [Change Logs](#change-logs)
* [Contribution Guidelines](#contribution-guidelines)
* [Reporting a Security Vulnerability](#security-help)


# <a name="about"></a>About

* PHP Controllers ~ 537 KB (on disk)
* Blade Template Views HTML with some JS CSS ~ 520 KB
* Survloop-Generated PHP Eloquent Data Table Models ~ 332 KB
* Survloop-Generated PHP Laravel Database Migration & Seeders ~ 2.1 MB
* <a href="https://packagist.org/packages/flexyourrights/openpolice-departments" target="_blank"
>Survloop-Generated PHP Police Departments & Oversight Seeders</a> ~ 9.4 MB

OpenPolice is an open-source, open data web app empowering citizens 
to prepare, file, and track reports of police conduct. The site 
helps users submit complaints or commendations to appropriate 
police oversight agencies. By allowing users to publish reports 
online, we aim to establish better public transparency and 
oversight of police activity in the U.S. OpenPolice extends 
<a href="https://github.com/rockhopsoft/survloop" target="_blank"
>Survloop</a>, which runs atop
<a href="https://laravel.com/" target="_blank">Laravel</a>.

<a href="https://openpolice.org" target="_blank">OpenPolice.org</a><br />

This software began as an internal tool to design our database, 
then prototype survey generation. Then it was adapted to the 
Laravel framework, and has continued to grow towards a 
content-management system for data-focused websites.

The upcoming OpenPolice web app can be tested out here, 
feedback welcome via the end of the <b>beta demo</b> submission process:<br />
<a href="https://openpolice.org/file-your-police-complaint" target="_blank"
>/file-your-police-complaint</a><br />
The resulting database designed using the engine, as well as 
the branching tree which specifies the user's experience: 
<a href="https://openpolice.org/db/OP" target="_blank">/db/OP</a><br />
<a href="https://openpolice.org/tree/complaint" target="_blank">/tree/complaint</a><br />
Among other methods, the resulting data can also be provided as 
XML included an automatically generated schema, eg.<br />
<a href="https://openpolice.org/complaint-xml-schema" target="_blank">/complaint-xml-schema</a><br />
<a href="https://openpolice.org/complaint-xml-example" target="_blank">/complaint-xml-example</a><br />
<a href="https://openpolice.org/complaint-xml-all" target="_blank">/complaint-xml-all</a>


# <a name="requirements"></a>Requirements

* php: >=7.4
* <a href="https://packagist.org/packages/laravel/laravel" target="_blank"
>laravel/laravel</a>: 8.*
* <a href="https://packagist.org/packages/rockhopsoft/survloop" target="_blank"
>rockhopsoft/survloop</a>: >=0.2
* <a href="https://packagist.org/packages/flexyourrights/openpolice-departments" target="_blank"
>flexyourrights/openpolice-departments</a>: 0.*
* <a href="https://packagist.org/packages/flexyourrights/openpolice-website" target="_blank"
>flexyourrights/openpolice-website</a>: 0.*

# <a name="getting-started"></a>Getting Started

## Installing OpenPolice

<a href="https://openpolice.org/how-to-install-local-openpolice" target="_blank"
>Full install instructions</a> also describe how to set up a development 
environment using VirutalBox, Vargrant, and Laravel's Homestead.

### Install Laravel, Survloop, & OpenPolice Using Composer
```
$ composer create-project laravel/laravel openpolice "8.*"
$ cd openpolice

```

Edit the environment file to connect the default MYSQL database:
```
$ nano .env
```
```
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Next, install Laravel's out-of-the-box user authentication tools, 
and push the vendor file copies where they need to be:
```
$ composer require laravel/ui flexyourrights/openpolice
$ php artisan ui vue --auth
```

From your Laravel installation's root directory, 
update `composer.json` to require and easily reference OpenPolice:
```
$ nano composer.json
```
```
...
"autoload": {
    ...
    "psr-4": {
        ...
        "FlexYourRights\\OpenPolice\\": "vendor/flexyourrights/openpolice/src/",
        "RockHopSoft\\Survloop\\": "vendor/rockhopsoft/survloop/src/",
    }
    ...
}, ...
```

Hopefully, editing `config/app.php` is no longer needed, 
but this can be tried if later steps break.
```
$ nano config/app.php
```
```
...
'providers' => [
    ...
    FlexYourRights\OpenPolice\OpenPoliceServiceProvider::class,
    RockHopSoft\Survloop\SurvloopServiceProvider::class,
    ...
],
...
'aliases' => [
    ...
    'OpenPolice' => 'FlexYourRights\OpenPolice\OpenPoliceFacade',
    'Survloop' => 'RockHopSoft\Survloop\SurvloopFacade',
    ...
], ...
```

For now, to apply database design changes to the same 
installation you are working in, depending on your server, 
you might also need something like this...
```
$ sudo su
$ chown -R www-data:33 storage database app/Models
```

Update composer, publish the package migrations, etc...
```
$ echo "0" | php artisan vendor:publish --force
$ php artisan optimize:clear
$ composer dump-autoload
```

If using the new DigitalOcean managed databases, 
you may need to tweak the laravel install:
```
$ nano database/migrations/2014_10_12_100000_create_password_resets_table.php
$ sudo nano database/migrations/2019_08_19_000000_create_failed_jobs_table.php
```
Add this line before the "Schema::create" line in each file:
```
\Illuminate\Support\Facades\DB::statement('SET SESSION sql_require_primary_key=0');
```

Then initialize the database:
```
$ php artisan migrate
$ php artisan db:seed --class=OpenPoliceSLSeeder
$ php artisan db:seed --class=OpenPoliceSeeder
$ php artisan db:seed --class=OpenPoliceDeptSeeder
$ php artisan db:seed --class=ZipCodeSeeder
```

### Initialize OpenPolice Installation

Then browsing to the home page should prompt 
you to create the first admin user account:

http://openpolice.local

If everything looks janky, then manually load the style sheets, etc:

http://openpolice.local/css-reload

After logging in as an admin, this link rebuilds many supporting files:

http://openpolice.local/dashboard/settings?refresh=2


# <a name="documentation"></a>Documentation

Once installed, documentation of this system's database design 
can be found at /dashboard/db/all . This system's user experience 
design for data entry can be found at /dashboard/tree/map?all=1&alt=1 
or publicly visible links like those above.

Better documentation is juuust beginning to be created...

<a href="https://openpolice.org/code-package-files-folders-and-classes" target="_blank"
>openpolice.org/code-package-files-folders-and-classes</a>

More on the Survloop level is also starting here: 
<a href="https://survloop.org/package-files-folders-classes" target="_blank"
>survloop.org/package-files-folders-classes</a>.

# <a name="roadmap"></a>Roadmap

Here's the TODO list for the next release (**1.0**). It's my first time building on Laravel, or GitHub. So sorry.

* [ ] Correct all issues needed for minimum viable product, and launch Open Police Complaints.
* [ ] Integrate options for MFA using Laravel-compatible package.
* [ ] Code commenting, learning and adopting more community norms.
* [ ] Add decent levels of unit testing. Hopefully improve the organization of objects/classes.

# <a name="change-logs"></a>Change Logs


# <a name="contribution-guidelines"></a>Contribution Guidelines

Please help educate me on best practices for sharing code in this community.
Please report any issue you find in the issues page.

# <a name="security-help"></a>Reporting a Security Vulnerability

We want to ensure that Open Police Complaints is a secure HTTP open data platform for everyone. 
If you've discovered a security vulnerability in OpenPolice.org, 
we appreciate your help in disclosing it to us in a responsible manner.

Publicly disclosing a vulnerability can put the entire community at risk. 
If you've discovered a security concern, please email us at rockhoppers *at* runbox.com. 
We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. 
We consider correspondence sent to rockhoppers *at* runbox.com our highest priority, 
and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a release will be deployed as soon as possible.
