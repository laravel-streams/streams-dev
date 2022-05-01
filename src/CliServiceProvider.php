<?php

namespace Streams\Cli;

use Illuminate\Support\Arr;
use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Streams\Cli\Console\MakeEntry;
use Streams\Ui\Support\Facades\UI;
use Streams\Cli\Console\MakeStream;
use Streams\Cli\Console\ListEntries;
use Streams\Cli\Console\ListStreams;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Streams\Cli\Console\Inputs\ArrayConsoleInput;
use Streams\Cli\Console\Inputs\ObjectConsoleInput;
use Streams\Cli\Console\Inputs\StringConsoleInput;
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
            'object' => ObjectConsoleInput::class,
            'array' => ArrayConsoleInput::class,
        ]);

        foreach ($inputs as $abstract => $concrete) {
            $this->app->bind("streams.cli.input_types.{$abstract}", $concrete);
        }
    }
}
