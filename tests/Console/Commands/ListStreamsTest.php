<?php

namespace Streams\Sdk\Tests\Console\Commands;

use Streams\Sdk\Tests\SdkTestCase;

class ListEntriesTest extends SdkTestCase
{

    public function test_it_can_list_streams()
    {
        $this->artisan('streams:list')
            ->expectsTable([
                'id'
            ], [
                [4]
            ]);
    }
}
