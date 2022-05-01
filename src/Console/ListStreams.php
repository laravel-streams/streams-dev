<?php

namespace Streams\Dev\Console;

use Illuminate\Console\Command;

class ListStreams extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'streams:list : List registered streams.
        {--query= : Query constraints.}
        {--columns= : Columns to display.}
        {--per-page=15 : Entries per page.}
        {--page= : Page to list.}';

    public function handle()
    {
        $this->call('entries:list', [
            'stream' => 'core.streams',
            '--query' => $this->option('query'),
            '--columns' => $this->option('columns'),
            '--per-page' => $this->option('per-page'),
            '--page' => $this->option('page'),
        ]);
    }
}
