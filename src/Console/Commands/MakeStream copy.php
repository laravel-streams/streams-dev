<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Console\Command;

class MakeStream extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'streams:create
        {input? : Query string formatted attributes.}
        {--update : Update if exists.}';

    protected $description = 'List registered streams.';

    public function handle()
    {
        $this->call('entries:create', [
            'stream' => 'core.streams',
            'input' => $this->argument('input'),
            '--update' => $this->option('update'),
        ]);
    }
}
