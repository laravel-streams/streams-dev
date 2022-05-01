<?php

namespace Streams\Dev;

use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class DevServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Dev\Console\MakeEntry::class,
                \Streams\Dev\Console\MakeStream::class,
                \Streams\Dev\Console\StreamsDescribe::class,
            ]);
        }
    }

    public function boot()
    {
        Field::macro('input', function(Command $command, Collection $input) {
            return $command->ask($this->name(), $this->default($input->get($this->handle)));
        });
    }
}
