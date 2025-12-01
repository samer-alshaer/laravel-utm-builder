<?php

declare(strict_types=1);

namespace Samer\UtmBuilder\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Samer\UtmBuilder\UtmBuilderServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UtmBuilderServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('utm-builder.base_url', 'https://example.com');
        $app['config']->set('utm-builder.client_url', 'https://client.example.com');
    }
}
