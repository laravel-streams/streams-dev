<?php

namespace Streams\Dev;

use Streams\Core\Stream\Stream;
use Streams\Core\Stream\StreamManager;
use Illuminate\Support\ServiceProvider;

class DevServiceProvider extends ServiceProvider
{

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__ . '/../resources/config/cp.php', 'streams.cp');

        // $this->publishes([
        //     base_path('vendor/streams/ui/resources/public')
        //     => public_path('vendor/streams/ui')
        // ], ['public']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Dev\Console\MakeEntry::class,
                \Streams\Dev\Console\StreamsDescribe::class,
            ]);
        }
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        // StreamManager::macro('factory', function () {
        //     return $this
        //             ->make($id)
        //             ->factory();
        // });

        // Stream::macro('factory', function () {

        // });
    }
}
