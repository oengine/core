# This is OEngine Platform

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oengine/core.svg?style=flat-square)](https://packagist.org/packages/oengine/core)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/oengine/core/run-tests?label=tests)](https://github.com/oengine/core/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/oengine/core/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/oengine/core/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/oengine/core.svg?style=flat-square)](https://packagist.org/packages/oengine/core)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.


## Installation

You can install the package via composer:

```bash
composer require oengine/core
```

Change User/Pass Default:

add code to .env file:

```bash
OENGINE_CORE_EMAIL=admin@oengine.local
OENGINE_CORE_PASSWORD=AdMin@123
```

Run the migrations and seed with:

```bash
php artisan core-install
```

Account:
```bash
usename: admin@oengine.local
password: AdMin@123
```

Comment route default : routes/web.php

```php
// Route::get('/', function () {
//     return view('welcome');
// });
```


You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="core-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="core-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="core-views"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nguyen Van Hau](https://github.com/oengine)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
