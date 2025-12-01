<?php

declare(strict_types=1);

namespace Samer\UtmBuilder\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Samer\UtmBuilder\Facades\Utm;
use Samer\UtmBuilder\UtmBuilder;
use Samer\UtmBuilder\UtmBuilderServiceProvider;

class UtmBuilderFeatureTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            UtmBuilderServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Utm' => Utm::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('utm-builder.base_url', 'https://example.com');
        $app['config']->set('utm-builder.client_url', 'https://client.example.com');
        $app['config']->set('utm-builder.presets', [
            'email' => [
                'utm_source' => 'email',
                'utm_medium' => 'email',
            ],
            'newsletter' => [
                'utm_source' => 'newsletter',
                'utm_medium' => 'email',
                'utm_campaign' => 'newsletter',
            ],
        ]);
    }

    public function test_facade_works(): void
    {
        $url = Utm::make()
            ->path('page')
            ->source('google')
            ->build();

        $this->assertStringContainsString('utm_source=google', $url);
    }

    public function test_helper_function_works(): void
    {
        $url = utm_link('page', 'email');

        $this->assertStringContainsString('utm_source=email', $url);
        $this->assertStringContainsString('utm_medium=email', $url);
    }

    public function test_client_helper_uses_client_url(): void
    {
        $url = utm_client('page', 'email');

        $this->assertStringStartsWith('https://client.example.com', $url);
    }

    public function test_preset_from_config(): void
    {
        $url = Utm::make()
            ->path('page')
            ->preset('newsletter')
            ->build();

        $this->assertStringContainsString('utm_source=newsletter', $url);
        $this->assertStringContainsString('utm_medium=email', $url);
        $this->assertStringContainsString('utm_campaign=newsletter', $url);
    }

    public function test_service_container_binding(): void
    {
        $builder = app('utm-builder');

        $this->assertInstanceOf(UtmBuilder::class, $builder);
    }

    public function test_utm_url_helper(): void
    {
        $url = utm_url('page', [
            'utm_source' => 'test',
            'utm_medium' => 'unit',
        ]);

        $this->assertStringContainsString('utm_source=test', $url);
        $this->assertStringContainsString('utm_medium=unit', $url);
    }

    public function test_blade_directive_compiles(): void
    {
        $compiled = $this->app['blade.compiler']->compileString('@utm("page", "email")');

        $this->assertStringContainsString('utm_link', $compiled);
    }

    public function test_client_static_method(): void
    {
        $url = UtmBuilder::client()
            ->path('page')
            ->source('test')
            ->build();

        $this->assertStringStartsWith('https://client.example.com', $url);
    }
}
