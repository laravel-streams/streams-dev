<?php

namespace Streams\Cli;

use Illuminate\Support\Arr;
use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Streams\Cli\Console\MakeEntry;
use Streams\Ui\Support\Facades\UI;
use Streams\Cli\Console\MakeStream;
use Streams\Cli\Console\ListEntries;
use Streams\Cli\Console\ListStreams;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Streams\Cli\Console\StreamsDescribe;
use Streams\Core\Field\Types\ArrayFieldType;

class CliServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Cli\Console\MakeEntry::class,
                \Streams\Cli\Console\MakeStream::class,
                \Streams\Cli\Console\ListEntries::class,
                \Streams\Cli\Console\ListStreams::class,
                \Streams\Cli\Console\StreamsDescribe::class,
            ]);
        }
    }

    public function boot()
    {
        $this->registerInputs();

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

    protected function registerInputs()
    {
        $inputs = Config::get('streams.ui.input_types', []);

        foreach ($inputs as $abstract => $concrete) {
            $this->app->bind("streams.ui.input_types.{$abstract}", $concrete);
        }

        Field::macro('input', function (array $attributes = []) {

            $attributes = Arr::add($attributes, 'field', $this);

            $this->input = $this->input ?: [
                'type' => $this->type,
            ];

            $attributes = $attributes + (array) $this->input;

            Arr::pull($attributes, 'type');

            if (!isset($this->input['type'])) {
                throw new \Exception("Missing input type for field [{$this->handle}] in stream [{$this->stream->id}]");
            }
dd($this->type);
            $this->app->make("streams.ui.input_types.{$this->type}");
            
            // return $this->once(
            //     $this->stream->id . $this->handle . 'input',
            //     function () use ($attributes) {

            //         Arr::pull($attributes, 'type');

            //         if (!isset($this->input['type'])) {
            //             throw new \Exception("Missing input type for field [{$this->handle}] in stream [{$this->stream->id}]");
            //         }

            //         return UI::make($this->input['type'], $attributes);
            //     }
            // );
        });
    }
}
