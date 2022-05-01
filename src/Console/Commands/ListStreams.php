<?php

namespace Streams\Cli\Console\Commands;

use Illuminate\Console\Command;

class ListStreams extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'streams:list
        {--query= : Query constraints.}
        {--columns=id,name,description : Columns to display.}
        {--per-page=15 : Entries per page.}
        {--page= : Page to list.}';

    protected $description = 'List registered streams.';

    public function handle()
    {
        $this->call('streams:entries', [
            'stream' => 'core.streams',
            '--query' => $this->option('query'),
            '--columns' => $this->option('columns'),
            '--per-page' => $this->option('per-page'),
            '--page' => $this->option('page'),
        ]);
    }
}
