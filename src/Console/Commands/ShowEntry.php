<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Streams\Core\Support\Facades\Streams;

class ShowEntry extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'entries:show
        {stream : The entry stream.}
        {entry : The entry identifier.}
        {--show= : Comma-seperated fields to show.}';

    protected $description = 'Display a single stream entry.';

    public function handle($page = 0)
    {
        $stream = Streams::make($this->argument('stream'));

        if (!$entry = $stream->repository()->find($id = $this->argument('entry'))) {
            $this->error("Entry [$id] not found.");
        }

        $fields = $stream->fields->pluck('handle')->all();

        if ($show = $this->option('show')) {
            $fields = array_intersect($fields, explode(',', $show));
        }

        $data = $entry->getAttributes();

        foreach ($data as &$value) {

            if (is_string($value)) {
                $value = Str::limit($value, 150);
            }

            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }

            $value = wordwrap((string) $value, 75);
        }

        $data = array_intersect_key($data, array_flip($fields));
        
        foreach ($data as $key => $value) {
            $rows[] = compact('key', 'value');
        }

        $this->table(['Field', 'Value'], $rows);
    }
}
