<?php

namespace Streams\Cli\Console\Inputs;

use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ConsoleInput
{
    public function ask(Command $command, Field $field, Collection $input)
    {
        return $command->ask($field->name(), $field->default($input->get($field->handle)));
    }
}
