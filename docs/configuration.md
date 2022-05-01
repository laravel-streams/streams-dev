---
title: Configuration
category: getting_started
intro: Configuring the DEV.
sort: 2
enabled: true
---

## Configuration Files

Published configuration files reside in `config/streams/`.

``` files
├── config/streams/
│   └── dev.php
```

### Publishing Configuration

Use the following command to publish configuration files.

```bash
php artisan vendor:publish --provider=Streams\\Dev\\DevServiceProvider --tag=config
```

The above command will copy configuration files from their package location to the directory mentioned above so that you can modify them directly and commit them to your version control system.

## Configuration

Below are the contents of the published configuration file:

```php
// config/streams/dev.php

return [

    /*
     * Determine if the DEV should be enabled.
     *
     * This is disabled by default because
     * The DEV is public by default.
     */
    'enabled' => env('STREAMS_DEV_ENABLED', true),

    /*
     * Specify the DEV prefix.
     */
    'prefix' => env('STREAMS_DEV_PREFIX', 'dev'),

    /*
     * Specify the DEV group middleware.
     *
     * This is designed to match out of the box
     * "app/Providers/RouteServiceProvider.php"
     * and "app/Http/Kernel.php" Laravel files.
     *
     * Changing this value will require
     * adjusting the above files.
     */
    'middleware' => env('STREAMS_DEV_MIDDLEWARE', 'dev'),

];
```

### DEV Middleware

DEV middleware an be configured in your application's HTTP kernel.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'dev' => [
        'throttle:60,1',
        'bindings',
        Streams\Dev\Http\Middleware\DevCache::class,
    ],
];
```

### DEV Routes File

The `app/Providers/RouteServiceProvider.php` file typically uses the `dev` middleware group when loading the `routes/dev.php` file. By default this is compatible and routes defined there will be properly prefixed and grouped.
