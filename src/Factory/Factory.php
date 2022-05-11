<?php

namespace Streams\Cli\Factory;

use Faker\Generator;
use Streams\Core\Field\Field;
use Illuminate\Support\Traits\Macroable;
use Streams\Core\Support\Traits\HasMemory;
use Illuminate\Support\Traits\ForwardsCalls;

class Factory
{
    use Macroable;
    use HasMemory;
    use ForwardsCalls;

    protected Field $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    public function create($parameters = [])
    {
        return $this->faker()->text();
    }

    public function faker(): Generator
    {
        return $this->once(__METHOD__, fn () => app('faker'));
    }
}
