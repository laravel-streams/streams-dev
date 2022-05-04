<?php

namespace Streams\Cli\Console\Commands;

use Illuminate\Console\Command;
use Streams\Core\Support\Facades\Streams;

class StreamSchema extends Command
{
    protected $signature = 'streams:schema
        {stream : The stream to show schema for.}';

    protected $description = 'Generate a JSON schema for the stream.';

    public function handle()
    {
        $this->info(Streams::schema($this->argument('stream'))->object()->toJson(JSON_PRETTY_PRINT));
    }
}
