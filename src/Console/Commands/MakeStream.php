<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class MakeStream extends Command
{

    // @todo this is temporary
    // needs to go through make:entry
    // because it may not be flat-file.
    protected $signature = 'make:stream {id}';

    protected $description = 'Create a stub stream.';

    public function handle()
    {
        $id = $this->argument('id');

        $contents = file_get_contents(__DIR__ . '/stubs/stream.stub');

        $contents = View::parse($contents, [
            'id' => $id,
            'name' => ucwords(str_replace(['-', '_'], ' ', $id)),
        ]);

        file_put_contents(
            $path = base_path("streams/{$id}.json"),
            $contents,
            JSON_PRETTY_PRINT
        );

        $this->info("Stream created: {$path}");
    }
}
