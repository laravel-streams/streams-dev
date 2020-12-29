<?php

namespace Streams\Dev\Console;

use Illuminate\Console\Command;
use Streams\Core\Support\Facades\Streams;

class StreamsMake extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:make
        {blueprint : The handle/gist of the desired blueprint.}
        {options? : Formatted options to send the blueprint.}
        {--json= : JSON input to send the blueprint.}';

    public function handle()
    {
        $blueprint = Streams::repository('dev.blueprints')->find($this->argument('blueprint'));
        
        $options = $this->argument('options');
        $json = json_decode($this->option('json'), true);
        
        dd($options);
    }
}
