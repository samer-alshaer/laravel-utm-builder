<?php

declare (strict_types = 1);

namespace Samer\UtmBuilder\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Samer\UtmBuilder\UtmBuilder;

class UtmBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        UtmBuilder::resetDefaults();

        UtmBuilder::setDefaults([
            'base_url'          => 'https://example.com',
            'client_url'        => 'https://client.example.com',
            'ref_prefix'        => 'ref_',
            'lowercase'         => true,
            'replace_spaces'    => true,
            'space_replacement' => '_',
            'presets'           => [
                'email' => [
                    'utm_source' => 'email',
                    'utm_medium' => 'email',
                ],
                'sms'   => [
                    'utm_source' => 'sms',
                    'utm_medium' => 'sms',
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        UtmBuilder::resetDefaults();
        parent::tearDown();
    }

    public function test_it_creates_basic_url(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->build();

        $this->assertEquals('https://example.com/page', $url);
    }

    public function test_it_adds_utm_source(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->source('google')
            ->build();

        $this->assertStringContainsString('utm_source=google', $url);
    }

    public function test_it_adds_utm_medium(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->medium('email')
            ->build();

        $this->assertStringContainsString('utm_medium=email', $url);
    }

    public function test_it_adds_utm_campaign(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->campaign('summer_sale')
            ->build();

        $this->assertStringContainsString('utm_campaign=summer_sale', $url);
    }

    public function test_it_adds_all_utm_parameters(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->source('google')
            ->medium('cpc')
            ->campaign('summer')
            ->term('keyword')
            ->content('banner')
            ->id('123')
            ->build();

        $this->assertStringContainsString('utm_source=google', $url);
        $this->assertStringContainsString('utm_medium=cpc', $url);
        $this->assertStringContainsString('utm_campaign=summer', $url);
        $this->assertStringContainsString('utm_term=keyword', $url);
        $this->assertStringContainsString('utm_content=banner', $url);
        $this->assertStringContainsString('utm_id=123', $url);
    }

    public function test_it_applies_preset(): void
    {
        $defaults = UtmBuilder::getDefaults();
        $this->assertArrayHasKey('presets', $defaults);
        $this->assertArrayHasKey('email', $defaults['presets']);

        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->preset('email')
            ->build();

        $this->assertStringContainsString('utm_source=email', $url);
        $this->assertStringContainsString('utm_medium=email', $url);
    }

    public function test_it_adds_custom_params(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->params(['custom' => 'value', 'another' => 'param'])
            ->build();

        $this->assertStringContainsString('custom=value', $url);
        $this->assertStringContainsString('another=param', $url);
    }

    public function test_it_adds_ref_with_prefix(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->ref('user', 123)
            ->build();

        $this->assertStringContainsString('ref_user=123', $url);
    }

    public function test_it_handles_path_with_leading_slash(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('/page')
            ->build();

        $this->assertEquals('https://example.com/page', $url);
    }

    public function test_it_handles_base_url_with_trailing_slash(): void
    {
        $url = UtmBuilder::make('https://example.com/')
            ->path('page')
            ->build();

        $this->assertEquals('https://example.com/page', $url);
    }

    public function test_it_converts_to_string(): void
    {
        $builder = UtmBuilder::make('https://example.com')
            ->path('page')
            ->source('test');

        $this->assertEquals($builder->build(), (string) $builder);
    }

    public function test_it_returns_array(): void
    {
        $builder = UtmBuilder::make('https://example.com')
            ->path('page')
            ->source('google')
            ->param('custom', 'value');

        $array = $builder->toArray();

        $this->assertArrayHasKey('url', $array);
        $this->assertArrayHasKey('base_url', $array);
        $this->assertArrayHasKey('path', $array);
        $this->assertArrayHasKey('utm_params', $array);
        $this->assertArrayHasKey('query_params', $array);
    }

    public function test_it_gets_utm_params(): void
    {
        $builder = UtmBuilder::make('https://example.com')
            ->source('google')
            ->medium('cpc');

        $params = $builder->getUtmParams();

        $this->assertEquals('google', $params['utm_source']);
        $this->assertEquals('cpc', $params['utm_medium']);
    }

    public function test_it_clears_utm_params(): void
    {
        $builder = UtmBuilder::make('https://example.com')
            ->source('google')
            ->clearUtm();

        $this->assertEmpty($builder->getUtmParams());
    }

    public function test_it_resets_all(): void
    {
        $builder = UtmBuilder::make('https://example.com')
            ->path('page')
            ->source('google')
            ->param('custom', 'value')
            ->reset();

        $this->assertEmpty($builder->getUtmParams());
        $this->assertEmpty($builder->getQueryParams());
    }

    public function test_it_checks_has_utm(): void
    {
        $builder = UtmBuilder::make('https://example.com');
        $this->assertFalse($builder->hasUtm());

        $builder->source('google');
        $this->assertTrue($builder->hasUtm());
    }

    public function test_it_handles_utm_array(): void
    {
        $url = UtmBuilder::make('https://example.com')
            ->path('page')
            ->utm([
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'test',
            ])
            ->build();

        $this->assertStringContainsString('utm_source=google', $url);
        $this->assertStringContainsString('utm_medium=cpc', $url);
        $this->assertStringContainsString('utm_campaign=test', $url);
    }

    public function test_fluent_api_returns_same_instance(): void
    {
        $builder = UtmBuilder::make('https://example.com');

        $this->assertSame($builder, $builder->path('page'));
        $this->assertSame($builder, $builder->source('google'));
        $this->assertSame($builder, $builder->medium('cpc'));
        $this->assertSame($builder, $builder->campaign('test'));
        $this->assertSame($builder, $builder->term('keyword'));
        $this->assertSame($builder, $builder->content('banner'));
    }
}
