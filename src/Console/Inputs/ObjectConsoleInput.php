<?php

namespace Streams\Cli\Console\Inputs;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Streams\Cli\Console\Inputs\ConsoleInput;

class ObjectConsoleInput extends ConsoleInput
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
