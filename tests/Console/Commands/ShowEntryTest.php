<?php

namespace Streams\Sdk\Tests\Console\Commands;

use Streams\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;

class ShowEntryTest extends TestCase
{

    public function test_it_can_show_an_entry()
    {
        $this->artisan('entries:show', [
            'films',
            4
        ])->expectsTable([
            'id'
        ], [
            4
        ]);
    }
}
