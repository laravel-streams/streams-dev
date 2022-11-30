<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Streams\Core\Stream\Stream;
use Streams\Core\Support\Facades\Streams;

class StreamsSchema extends Command
{
    protected $signature = 'streams:schema
        {--include= : Comma seperated streams to include.}
        {--exclude= : Comma seperated streams to exclude.}
        {--path= : The path to write files to.}';

    protected $description = 'Generate schema files for configured streams.';

    public function handle()
    {
        $streams = Streams::collection();

        if ($include = $this->option('include')) {
            $streams = $streams->whereIn('id', explode(',', $include));
        }

        if ($exclude = $this->option('exclude')) {
            $streams = $streams->whereNotIn('id', explode(',', $exclude));
        }

        $path = base_path($this->option('path'));

        $streams->each(fn ($stream) => $this->writeSchemaFile($stream, $path));
    }

    protected function writeSchemaFile(Stream $stream, string $path)
    {
        $file = $stream->id . '.schema.json';
        $file = rtrim($path, '/') . '/' . $file;

        $schema = $stream->schema();

        $json = array_merge(
            $schema->tag()->toArray(),
            $schema->object()->toArray(),
        );

        $contents = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents($file, $contents);

        $this->info($file);
    }
}
