# Installation

## Downloading

This package is added to Laravel projects as a dependency by requiring it with Composer.

```bash
composer require streams/sdk:1.0.x-dev
```

The Streams SDK comes pre-configured with the [Streams starter application](/docs/installation) and some of the [examples](/docs/examples).

## Updating

From within your project, use Composer to update this individual package:

```bash
composer update streams/sdk --with-dependencies
```

You can also update your entire project using `composer update`.

# Templates

Publish templates with the `vendor:publish` command:

```bash
php artisan vendor:publish --tag=examples
```

Or target specific example templates:

```bash
php artisan vendor:publish --tag=files-example
php artisan vendor:publish --tag=users-example
```
