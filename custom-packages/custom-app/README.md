# New generation of administration

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brackets-by-triad/craftable-pro.svg?style=flat-square)](https://packagist.org/packages/brackets-by-triad/craftable-pro)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/brackets-by-triad/craftable-pro/run-tests?label=tests)](https://github.com/brackets-by-triad/craftable-pro/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/brackets-by-triad/craftable-pro/Check%20&%20fix%20styling?label=code%20style)](https://github.com/brackets-by-triad/craftable-pro/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/brackets-by-triad/craftable-pro.svg?style=flat-square)](https://packagist.org/packages/brackets-by-triad/craftable-pro)

## Installation

After obtaining a valid [Craftable PRO license](https://craftablepro.pro/) you need to add our private repository to your project:
```bash
composer config repositories.craftable-pro vcs git@github.com:BRACKETS-by-TRIAD/craftable-pro.git
```

You can install the package via composer:

```bash
composer require brackets/craftable-pro
```

Then you need to install the package (it will publish resources, migrations, configs, edit some configuration, ...) with:

```bash
php artisan craftable-pro:install
```

and finally you run:
```bash
npm install
npm run craftable-pro:dev
```

## Development

To develop this package, we recommend you to start on a fresh Laravel instance using Sail:
```bash
curl -s "https://laravel.build/craftable-pro-dev" | bash

cd craftable-pro-dev

./vendor/bin/sail up -d
```

and then run these commands:

```bash
git clone git@github.com:BRACKETS-by-TRIAD/craftable-pro.git
composer config repositories.craftable-pro path craftable-pro
composer require brackets/craftable-pro
./vendor/bin/sail artisan craftable-pro:install
./vendor/bin/sail artisan vendor:publish --tag=craftable-pro-seeders
./vendor/bin/sail artisan db:seed --class=DummyDataSeeder
./vendor/bin/sail npm install
./vendor/bin/sail npm run craftable-pro:dev
```

## Testing

```bash
composer test
```
