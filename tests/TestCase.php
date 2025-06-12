<?php

declare(strict_types=1);

namespace CommandValidator;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CommandValidatorTestServiceProvider::class,
        ];
    }
}
