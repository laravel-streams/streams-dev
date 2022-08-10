<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Arr;
use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Streams\Core\Support\Facades\Streams;

class StreamsTap extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'streams:tap
        {tap : The tap URL.}
        {input? : Query string formatted input.}';

    protected $description = 'Install something via tap.';

    public function handle()
    {
        
    }
}
