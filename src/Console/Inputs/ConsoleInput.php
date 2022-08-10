<?php

namespace Streams\Sdk\Console\Inputs;

use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ConsoleInput
{
    public function __construct(public Field $field)
    {
    }

    public function ask(Command $command, Collection $input)
    {
        return $command->ask(
            $this->field->name(),
            $this->field->default($input->get($this->field->handle))
        );
    }
}
