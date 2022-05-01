<?php

namespace Streams\Dev;

use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Streams\Core\Field\Types\ArrayFieldType;

class DevServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Dev\Console\MakeEntry::class,
                \Streams\Dev\Console\MakeStream::class,
                \Streams\Dev\Console\ListEntries::class,
                \Streams\Dev\Console\ListStreams::class,
                \Streams\Dev\Console\StreamsDescribe::class,
            ]);
        }
    }

    public function boot()
    {
        Field::macro('input', function (Command $command, Collection $input) {
            return $command->ask($this->name(), $this->default($input->get($this->handle)));
        });

        // ArrayFieldType::macro('input', function (Command $command, Collection $input) {

        //     if (!$command->ask('Add [' . $this->handle . '] items?')) {
        //         return $items = [];
        //     }

        //     while ($command->ask('Add another?', true)) {
        //         $items[] = $command->ask($this->handle . '[]');
        //     }

        //     return $items;
        // });
    }
}
