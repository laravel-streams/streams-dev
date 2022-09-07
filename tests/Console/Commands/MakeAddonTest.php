<?php

namespace Streams\Sdk\Tests\Console\Commands;

use Streams\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;

class MakeAddonTest extends TestCase
{

    public function test_it_makes_addons()
    {
        $this->markTestSkipped('Not ready.');

        // Artisan::call('make:addon', [
        //     'streams/widgets',
        // ]);
    }
}
