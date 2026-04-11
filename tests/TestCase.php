<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = storage_path('framework/testing/views-'.getmypid().'-'.bin2hex(random_bytes(4)));

        File::ensureDirectoryExists($compiledPath);

        config()->set('view.compiled', $compiledPath);
    }
}
