<?php

namespace Streams\Core\Field\Schema;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class MultiselectSchema extends ArrSchema
{

    public function type(): Schema
    {
        return Schema::array($this->field->handle)
            ->items(Schema::string()->enum(...array_keys($this->field->options())));
    }
}
