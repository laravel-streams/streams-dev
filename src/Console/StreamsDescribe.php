<?php

namespace Streams\Dev\Console;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Streams\Core\Support\Facades\Streams;

class StreamsDescribe extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:describe : Generate a stream describing the target data source.
        {target : The database, table, or model to describe.}';

    public function handle()
    {
        $source = null;

        $options = collect([]);

        $target = $this->argument('target');

        if (strpos($target, ':')) {
            list($source, $target) = explode(':', $target);
        }

        $options->target = $target;

        if (!$source && filter_var($target, FILTER_VALIDATE_URL)) {
            $source = 'url';
        }

        if (!$source && is_string($target) && json_decode($target) !== null) {
            $source = 'json';
        }

        if (!$source && Schema::hasTable($target)) {
            $source = 'database';
        }

        if (!$source && class_exists($target) && method_exists(app($target), 'getStream')) {
            $source = 'stream';
        }

        if (!$source && class_exists($target) && !method_exists(app($target), 'getStream')) {
            $source = 'eloquent';
        }

        if (!$source) {
            return $this->error("Source type for [{$target}] could not be determined.");
        }

        $stream = $this->{Str::camel('describe_' . $source)}($options);

        $id = Arr::pull($stream, 'id');

        File::put(base_path("streams/{$id}.json"), json_encode($stream, JSON_PRETTY_PRINT));

        $this->info('Wrote ' . base_path("streams/{$id}.json"));
    }

    protected function describeDatabase(Collection $options)
    {
        $stream = [
            'id' => $options->target,
            'handle' => $options->target,
            'source' => [
                'type' => 'database',
                'table' => $options->target,
            ],
            'fields' => [],
        ];

        $schema = DB::getSchemaBuilder();

        $columns = $schema->getColumnListing($options->target);

        foreach ($columns as $column) {

            $type = $schema->getColumnType($options->target, $column);

            $stream['fields'][$column] = [
                'type' => $type,
            ];
        }

        return $stream;
    }

    protected function describeEloquent(Collection $options)
    {
        $instance = app($options->target);

        $stream = [
            'id' => $instance->getTable(),
            'handle' => $instance->getTable(),
            'source' => [
                'type' => 'eloquent',
                'model' => $options->target,
            ],
            'fields' => [],
        ];

        $schema = DB::getSchemaBuilder();

        $columns = $schema->getColumnListing($options->target);

        foreach ($columns as $column) {

            $type = $schema->getColumnType($options->target, $column);

            $stream['fields'][$column] = [
                'type' => $type,
            ];
        }

        return $stream;
    }

    protected function describeStream(Collection $options)
    {
        $model = app($options->target);

        $instance = $model->getStream();

        $stream = [
            'id' => $model->getTable(),
            'handle' => $instance->getNamespace() . '.' . $instance->getSlug(),
            'source' => [
                'type' => 'eloquent',
                'model' => $options->target,
            ],
            'fields' => [],
        ];

        $stream['fields']['id'] = [
            'type' => 'integer',
        ];

        foreach ($instance->getAssignments() as $slug => $assignment) {

            $stream['fields'][$assignment->field->slug] = [
                'type' => $assignment->field->getType()->getSlug(),
            ];

            if ($assignment->required) {
                $stream['fields'][$assignment->field->slug]['required'] = true;
            }

            if ($assignment->unique) {
                $stream['fields'][$assignment->field->slug]['unique'] = true;
            }

            if ($assignment->config || $assignment->field->config) {

                $config = array_merge($assignment->field->config, $assignment->config);

                $stream['fields'][$assignment->field->slug]['config'] = $config;
            }
        }

        return $stream;
    }
}
