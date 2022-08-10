<?php

namespace Streams\Sdk\Console\Inputs;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Streams\Sdk\Console\Inputs\ConsoleInput;

class ArrayConsoleInput extends ConsoleInput
{
    public function ask(Command $command, Collection $input)
    {
        return (array) parent::ask($command, $input);
        // return $command->ask(
        //     $this->field->name(),
        //     $this->field->default($input->get($this->field->handle))
        // );
    }
}
