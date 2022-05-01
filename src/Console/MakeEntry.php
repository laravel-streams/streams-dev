<?php

namespace Streams\Dev\Console;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Streams\Core\Support\Facades\Streams;

class MakeEntry extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'make:entry
        {stream : The entry stream.}
        {input? : Formatted entry input.}';

    public function handle()
    {
        $stream = Streams::make($this->argument('stream'));

        $string = $this->argument('input');
        
        parse_str($string, $input);

        if ($id = Arr::get($input, $stream->config('key_name', 'id'))) {

            $entry = $stream->repository()->find($id);

            $input = array_merge((array) $entry, $input);
        }
        
        if (!$input) {
            return $this->error('Truly, one cannot create something from nothing.');
        }

        $messages = $stream->validator($input)->messages()->all();

        if ($messages) {

            array_walk($messages, function ($message) {
                $this->error($message);
            });

            return 1;
        }

        $entry = $stream->repository()->create($input);

        // If has path in config might be better?
        // if ($stream->source['type'] == 'filebase') {
        //     $this->info('Created: ' . base_path($stream->source['path'] . '/' . $entry->id . '.' . Arr::get($stream->source, 'format', 'md')));
        // }

        $this->info(json_encode([
            'message' => 'Entry created successfully.',
            'data' => $entry,
        ]));
    }

    public function askForInput($stream, &$input)
    {
        foreach ($stream->fields as $field) {
            if (!is_null($value = $this->ask("{$field->name()}: " . ($field->isRequired() ? '(*)' : '')))) {
                $input[$field->handle] = $value;
            }
        }
    }
}
