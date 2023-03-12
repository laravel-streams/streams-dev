<?php

namespace Streams\Sdk\Console\Inputs;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Streams\Sdk\Console\Inputs\ConsoleInput;

class BooleanConsoleInput extends ConsoleInput
{
    public function ask(Command $command, Collection $input)
    {
        if ($input->has($this->field->handle)) {
            $default = $input->get($this->field->handle);
        } else {
            $default = $this->field->config('default');
        }

        return $command->ask(
            $this->field->name() . ' (y/n)',
            $this->field->default($default) ? 'y' : 'n'
        );
    }
}
