
# FlexYourRights/OpenPolice

[![Laravel](https://img.shields.io/badge/Laravel-5.8-orange.svg?style=flat-square)](http://laravel.com)
[![SurvLoop](https://img.shields.io/badge/SurvLoop-0.1-orange.svg?style=flat-square)](https://github.com/wikiworldorder/survloop)
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

* PHP Controllers ~ 385 KB (on disk)
* Blade Template Views HTML with some JS CSS ~ 373 KB
* SurvLoop-Generated PHP Eloquent Data Table Models ~ 311 KB
* SurvLoop-Generated PHP Laravel Database Migration & Seeders ~ 1.5 MB
* <a href="https://packagist.org/packages/flexyourrights/openpolice-departments" target="_blank">SurvLoop-Generated PHP Police Departments & Oversight Seeders</a> ~ 9.9 MB

Open Police is an open-source, open data web app empowering citizens to prepare, file, and track reports of police 
conduct. The site helps users submit complaints or commendations to appropriate police oversight agencies. By allowing 
users to publish reports online, we aim to establish better public transparency and oversight of police activity in the 
U.S.. Open Police extends <a href="https://github.com/wikiworldorder/survloop" target="_blank">SurvLoop</a>, which runs atop
<a href="https://laravel.com/" target="_blank">Laravel</a>.

<a href="https://openpolice.org" target="_blank">OpenPolice.org</a><br />

It is currently in continued, heavy development, with the pilot program rolling now in early 2019. 
I plan to provide more documentation in the coming weeks. Thank you for your interest and patience!
This software began as an internal tool to design our database, then prototype survey generation. Then it was adapted to the 
Laravel framework, and has continued to grow towards a content-management system for data-focused websites.

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


# <a name="requirements"></a>Requirements

* php: >=7.2
* <a href="https://packagist.org/packages/laravel/laravel" target="_blank">laravel/laravel</a>: 5.8.*
* <a href="https://packagist.org/packages/wikiworldorder/survloop" target="_blank">wikiworldorder/survloop</a>: 0.1.*
* <a href="https://packagist.org/packages/flexyourrights/openpolice-departments" target="_blank">flexyourrights/openpolice-departments</a>: 0.1.*
* <a href="https://packagist.org/packages/flexyourrights/openpolice-website" target="_blank">flexyourrights/openpolice-website</a>: 0.1.*

# <a name="getting-started"></a>Getting Started

## Installing Open Police Complaints with Laradock

First, <a href="https://www.docker.com/get-started" target="_blank">install Docker</a> on Mac, Windows, or an online server. 
Then grab a copy of Laravel (last tested with v5.8.3)...
```
$ git clone https://github.com/laravel/laravel.git opc
$ cd opc
```

Next, install and boot up Laradock (last tested with v7.14).
```
$ git submodule add https://github.com/Laradock/laradock.git
$ cd laradock
$ cp env-example .env
$ docker-compose up -d nginx mysql phpmyadmin redis workspace
```

After Docker finishes booting up your containers, enter the mysql container with the root password, "root". This seems to fix things for the latest version of MYSQL.
```
$ docker-compose exec mysql bash
# mysql --user=root --password=root default
mysql> ALTER USER 'default'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
mysql> exit;
$ exit
```

At this point, you can optionally browse to <a href="http://localhost:8080" target="_blank">http://localhost:8080</a> for PhpMyAdmin.
```
Server: mysql
Username: default
Password: secret
```

Finally, enter Laradock's workspace container to download and run the Open Police installation script.
```
$ docker-compose exec workspace bash
# git clone https://github.com/flexyourrights/docker-openpolice.git
# chmod +x ./docker-openpolice/bin/*.sh
# ./docker-openpolice/bin/openpolice-laradock-postinstall.sh
```
And if all has gone well, you'll be asked to create a master admin user account when you browse to <a href="http://localhost/" target="_blank">http://localhost/</a>. If it loads, but looks janky (without CSS), reload the page once... and hopefully it looks like a fresh install.


## Installing OpenPolice without Laradock

The instructions below include the needed steps to install Laravel, SurvLoop, and Open Police Complaints.
For more on creating environments to host Laravel, you can find more instructions 
<a href="https://survloop.org/how-to-install-laravel-on-a-digital-ocean-server" target="_blank">on SurvLoop.org</a>.

### Use OpenPolice Install Script

If you've got PHP running, and Composer installed, you can just run this install script...

```
$ git clone https://github.com/flexyourrights/docker-openpolice.git
$ chmod +x ./docker-openpolice/bin/*.sh
$ ./docker-openpolice/bin/openpolice-compose-install.sh ProjectFolderName
```

* Load in the browser to create super admin account and get started.

### Copy & Paste Install Commands

* Use Composer to install Laravel with default user authentication, one required package:

```
$ composer global require "laravel/installer"
$ composer create-project laravel/laravel ProjectFolderName "5.8.*"
$ cd ProjectFolderName
$ php artisan key:generate
$ php artisan make:auth
$ composer require flexyourrights/openpolice
$ sed -i 's/App\\User::class/SurvLoop\\Models\\User::class/g' config/auth.php
```

* Update composer, publish the package migrations, etc...

```
$ echo "0" | php artisan vendor:publish --force
$ php artisan migrate
$ composer dump-autoload
$ php artisan db:seed --class=SurvLoopSeeder
$ php artisan db:seed --class=ZipCodeSeeder
$ php artisan db:seed --class=OpenPoliceSeeder
$ php artisan db:seed --class=OpenPoliceDeptSeeder
```

* For now, to apply database design changes to the same installation you are working in, depending on your server, 
you might also need something like this...

```
$ chown -R www-data:33 app/Models
$ chown -R www-data:33 database
```

* Load in the browser to create super admin account and get started.


# <a name="documentation"></a>Documentation

Once installed, documentation of this system's database design can be found at /dashboard/db/all . This system's user 
experience design for data entry can be found at /dashboard/tree/map?all=1&alt=1 
or publicly visible links like those above.

More on the SurvLoop level is also starting here: <a href="https://survloop.org/package-files-folders-classes" target="_blank">https://survloop.org/package-files-folders-classes</a>.

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
If you've discovered a security concern, please email us at wikiworldorder *at* protonmail.com. 
We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. 
We consider correspondence sent to wikiworldorder *at* protonmail.com our highest priority, 
and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a release will be deployed as soon as possible.
