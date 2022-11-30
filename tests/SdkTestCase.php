<?php

namespace Streams\Sdk\Tests;

use Streams\Testing\TestCase;
use Streams\Sdk\SdkServiceProvider;

abstract class SdkTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SdkServiceProvider::class];
    }
}
