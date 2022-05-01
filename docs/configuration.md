---
title: Configuration
category: getting_started
intro: Configuring the CLI.
sort: 2
enabled: true
---

## Configuration Files

Published configuration files reside in `config/streams/`.

``` files
├── config/streams/
│   └── cli.php
```

### Publishing Configuration

Use the following command to publish configuration files.

```bash
php artisan vendor:publish --provider=Streams\\Cli\\CliServiceProvider --tag=config
```

The above command will copy configuration files from their package location to the directory mentioned above so that you can modify them directly and commit them to your version control system.

## Configuration

Below are the contents of the published configuration file:

```php
// config/streams/cli.php

return [

    /*
     * Determine if the CLI should be enabled.
     *
     * This is disabled by default because
     * The CLI is public by default.
     */
    'enabled' => env('STREAMS_CLI_ENABLED', true),

    /*
     * Specify the CLI prefix.
     */
    'prefix' => env('STREAMS_CLI_PREFIX', 'cli'),

    /*
     * Specify the CLI group middleware.
     *
     * This is designed to match out of the box
     * "app/Providers/RouteServiceProvider.php"
     * and "app/Http/Kernel.php" Laravel files.
     *
     * Changing this value will require
     * adjusting the above files.
     */
    'middleware' => env('STREAMS_CLI_MIDDLEWARE', 'cli'),

];
```

### CLI Middleware

CLI middleware an be configured in your application's HTTP kernel.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'cli' => [
        'throttle:60,1',
        'bindings',
        Streams\Cli\Http\Middleware\CliCache::class,
    ],
];
```

### CLI Routes File

The `app/Providers/RouteServiceProvider.php` file typically uses the `cli` middleware group when loading the `routes/cli.php` file. By default this is compatible and routes defined there will be properly prefixed and grouped.
