<?php

namespace Streams\Dev;

use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Streams\Core\Stream\Stream;
use Illuminate\Support\Collection;
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
                \Streams\Dev\Console\MakeStream::class,
                \Streams\Dev\Console\StreamsDescribe::class,
            ]);
        }
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        Field::macro('input', function(Command $command, Collection $input) {
            return $command->ask($this->name(), $this->default($input->get($this->handle)));
        });
        
        // StreamManager::macro('factory', function () {
        //     return $this
        //             ->make($id)
        //             ->factory();
        // });

        // Stream::macro('factory', function () {

        // });
    }
}
