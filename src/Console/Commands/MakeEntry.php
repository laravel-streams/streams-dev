<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Arr;
use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
        {input? : Query string formatted attributes.}
        {--update : Update if exists.}';

    protected $description = 'Make a stream entry.';

    public function handle()
    {
        $stream = Streams::make($this->argument('stream'));

        parse_str($this->argument('input'), $input);

        $input = new Collection($input);

        $key = Arr::get($input, $stream->config('key_name', 'id'));

        foreach ($stream->fields as $field) {
            if (!$input->has($field->handle)) {
                $this->askForInput($field, $input);
            }
        }

        $fresh = !$this->option('update');

        $validator = $stream->validator($input->all(), $fresh);

        $valid = $validator->passes();

        if (!$valid) {

            $messages = $validator->messages();

            foreach ($messages->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }

            return;
        }

        if (!$key || !$instance = $stream->repository()->find($key)) {
            $instance = $stream->repository()->newInstance($input->all());
        }

        if ($key && $instance) {
            foreach ($input as $key => $value) {
                $instance->{$key} = $value;
            }
        }

        $instance->save();

        $this->info(json_encode($instance));
    }

    protected function askForInput(Field $field, Collection $input)
    {
        $value = $field->console()->ask($this, $input);

        $input->put($field->handle, $value);
    }
}
