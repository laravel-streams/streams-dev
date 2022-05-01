<?php

namespace Streams\Cli;

use Streams\Core\Field\Field;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Streams\Cli\Console\Inputs\ArrayConsoleInput;
use Streams\Cli\Console\Inputs\ObjectConsoleInput;
use Streams\Cli\Console\Inputs\StringConsoleInput;

class CliServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Streams\Cli\Console\Commands\MakeEntry::class,
                \Streams\Cli\Console\Commands\ShowEntry::class,
                \Streams\Cli\Console\Commands\MakeStream::class,
                \Streams\Cli\Console\Commands\ListEntries::class,
                \Streams\Cli\Console\Commands\ListStreams::class,
                //\Streams\Cli\Console\Commands\StreamsDescribe::class,
            ]);
        }
    }

    public function boot()
    {
        $this->registerInputs();

        Field::macro('console', function () {

            if (!App::has("streams.cli.input_types.{$this->type}")) {
                throw new \Exception("Missing CLI input [{$this->type}] required for field [{$this->handle}] in stream [{$this->stream->id}]");
            }

            return App::make("streams.cli.input_types.{$this->type}", ['field' => $this]);
        });
    }

    protected function registerInputs()
    {
        $inputs = Config::get('streams.cli.input_types', [
            'string' => StringConsoleInput::class,
            'slug' => StringConsoleInput::class,
            'object' => ObjectConsoleInput::class,
            'array' => ArrayConsoleInput::class,
        ]);

        foreach ($inputs as $abstract => $concrete) {
            $this->app->bind("streams.cli.input_types.{$abstract}", $concrete);
        }
    }
}
