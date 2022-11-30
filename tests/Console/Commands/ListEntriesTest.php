<?php

namespace Streams\Sdk\Tests\Console\Commands;

use Streams\Sdk\Tests\SdkTestCase;

class ListEntriesTest extends SdkTestCase
{

    public function test_it_can_list_entries()
    {
        $this->artisan('entries:list', [
            'stream' => 'films',
            'entry' => 4
        ])->expectsTable([
            'id'
        ], [
            [4]
        ]);
    }
}
