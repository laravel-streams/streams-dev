<?php

namespace Streams\Cli;

use Streams\Core\Field\Field;
use Streams\Core\Stream\Stream;
use Illuminate\Support\Facades\App;
use Streams\Cli\Schema\EntrySchema;
use Streams\Cli\Schema\FieldSchema;
use Streams\Cli\Schema\StreamSchema;
use Illuminate\Support\Facades\Config;
use Streams\Core\Stream\StreamManager;
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
                \Streams\Cli\Console\Commands\StreamSchema::class,
                \Streams\Cli\Console\Commands\DescribeStream::class,
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

        Stream::macro('schema', function() {
            return new StreamSchema($this);
        });
        
        Field::macro('schema', function() {
            $name = $this->getSchemaName();
            return new $name($this);
        });

        Field::macro('getSchemaName', function() {
            return FieldSchema::class;
        });

        // public function schema(): FieldSchema
        // {
        //     $schema = $this->config('schema', $this->getSchemaName());

        //     return new $schema($this);
        // }

        // protected function getSchemaName()
        // {
        //     return FieldSchema::class;
        // }

        // public function schema(): EntrySchema
        // {
        //     return static::once($this->id . __METHOD__, fn () => $this->newSchema());
        // }

        // protected function newSchema(): EntrySchema
        // {
        //     $schema  = $this->config('schema', EntrySchema::class);

        //     return new $schema($this);
        // }

        StreamManager::macro('schema', function($stream) {
            return $this->make($stream)->schema();
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
