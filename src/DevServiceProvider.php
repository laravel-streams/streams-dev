<?php

namespace Streams\Dev;

use Illuminate\Support\ServiceProvider;

class DevServiceProvider extends ServiceProvider
{

    /**
     * The class aliases.
     *
     * @var array
     */
    public $aliases = [];

    /**
     * The class bindings.
     *
     * @var array
     */
    public $bindings = [];

    /**
     * The singleton bindings.
     *
     * @var array
     */
    public $singletons = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__ . '/../resources/config/cp.php', 'streams.cp');

        // $this->publishes([
        //     base_path('vendor/streams/ui/resources/public')
        //     => public_path('vendor/streams/ui')
        // ], ['public']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Dev\Console\StreamsDescribe::class,
            ]);
        }
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        // Streams::register([
        //     'handle' => 'dev.blueprints',
        //     'source' => [
        //         'path' => 'streams/dev/blueprints',
        //         'format' => 'json',
        //     ],
        //     'config' => [
        //         'prototype' => 'Streams\\Dev\\Blueprint\\Blueprint',
        //     ],
        //     'fields' => [
        //         'template' => [],
        //         'parent' => [
        //             'type' => 'relationship',
        //             'related' => 'cp.navigation',
        //         ],
        //     ],
        // ]);
    }
}
