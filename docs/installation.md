# Installation

- [Composer Installation](#composer-installation)
- [Manual Installation](#manual-installation)

This version requires PHP 8.2 or later and CodeIgniter 4.7 or later.

This package does not install the browser-side htmx library. Install the htmx 4 major version explicitly:

```console
npm install htmx.org@4
```

An unversioned `npm install htmx.org` may still install htmx 2 during the htmx 4 release transition. See the [htmx 4.0.0 release notes](https://four.htmx.org/announcements/2026-08-28-htmx-4.0.0-is-released) for details. Include the resulting JavaScript bundle in your page's `head` element.

## Composer Installation

Install the PHP integration with Composer:

```console
composer require michalsn/codeigniter-htmx
```

## Manual Installation

In the example below we will assume, that files from this project will be located in `app/ThirdParty/htmx` directory.

Download this project and then enable it by editing the `app/Config/Autoload.php` file and adding the `Michalsn\CodeIgniterHtmx` namespace to the `$psr4` array. You also have to add `Common.php` to the `$files` array, like in the below example:

```php
<?php

...

public $psr4 = [
    APP_NAMESPACE => APPPATH, // For custom app namespace
    'Config'      => APPPATH . 'Config',
    'Michalsn\CodeIgniterHtmx' => APPPATH . 'ThirdParty/htmx/src',
];

...

public $files = [
    APPPATH . 'ThirdParty/htmx/src/Common.php',
];
```
