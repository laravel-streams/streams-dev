<?php

namespace Streams\Cli\Console\Commands;

use Illuminate\Console\Command;

class ShowStream extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'streams:show
    {stream : The stream identifier.}
    {--show= : Comma-seperated fields to show.}';

    protected $description = 'List registered streams.';

    public function handle()
    {
        $this->call('entries:show', [
            'stream' => 'core.streams',
            'entry' => $this->argument('stream'),
            '--show' => $this->option('show'),
        ]);
    }
}
