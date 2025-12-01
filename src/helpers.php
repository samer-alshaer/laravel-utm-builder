<?php

declare(strict_types=1);

use Samer\UtmBuilder\UtmBuilder;

if (! function_exists('utm')) {
    /**
     * Get a new UTM builder instance.
     *
     * @param  string|null  $baseUrl  Optional base URL
     * @return UtmBuilder
     */
    function utm(?string $baseUrl = null): UtmBuilder
    {
        return UtmBuilder::make($baseUrl);
    }
}

if (! function_exists('utm_link')) {
    /**
     * Generate a UTM link quickly.
     *
     * @param  string  $path  URL path
     * @param  string  $preset  Preset name (optional)
     * @param  array  $extra  Additional parameters (optional)
     * @return string
     */
    function utm_link(string $path, string $preset = '', array $extra = []): string
    {
        $builder = UtmBuilder::make()->path($path);

        if ($preset) {
            $builder->preset($preset);
        }

        if (! empty($extra)) {
            $builder->params($extra);
        }

        return $builder->build();
    }
}

if (! function_exists('utm_client')) {
    /**
     * Generate a UTM link for client website.
     *
     * @param  string  $path  URL path
     * @param  string  $preset  Preset name (optional)
     * @param  array  $extra  Additional parameters (optional)
     * @return string
     */
    function utm_client(string $path, string $preset = '', array $extra = []): string
    {
        $builder = UtmBuilder::client()->path($path);

        if ($preset) {
            $builder->preset($preset);
        }

        if (! empty($extra)) {
            $builder->params($extra);
        }

        return $builder->build();
    }
}

if (! function_exists('utm_url')) {
    /**
     * Generate a UTM URL with custom base URL.
     *
     * @param  string  $url  Full URL or path
     * @param  array  $utm  UTM parameters
     * @param  array  $extra  Additional query parameters
     * @return string
     */
    function utm_url(string $url, array $utm = [], array $extra = []): string
    {
        // Check if it's a full URL or just a path
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($url);
            $baseUrl = $parsed['scheme'] . '://' . $parsed['host'];
            $path = $parsed['path'] ?? '';
        } else {
            $baseUrl = config('utm-builder.base_url', config('app.url', ''));
            $path = $url;
        }

        $builder = UtmBuilder::make($baseUrl)->path(ltrim($path, '/'));

        if (! empty($utm)) {
            $builder->utm($utm);
        }

        if (! empty($extra)) {
            $builder->params($extra);
        }

        return $builder->build();
    }
}
