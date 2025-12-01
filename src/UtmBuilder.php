<?php

declare(strict_types=1);

namespace Samer\UtmBuilder;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class UtmBuilder
{
    use Macroable;

    protected string $baseUrl;
    protected string $path = '';
    protected array $utmParams = [];
    protected array $queryParams = [];

    /**
     * Create a new UtmBuilder instance.
     */
    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? config('utm-builder.base_url', config('app.url', ''));
    }

    /**
     * Static factory method for fluent API.
     */
    public static function make(?string $baseUrl = null): static
    {
        return new static($baseUrl);
    }

    /**
     * Create instance with client URL from config.
     */
    public static function client(): static
    {
        return new static(config('utm-builder.client_url'));
    }

    /**
     * Set the base URL.
     */
    public function baseUrl(string $url): static
    {
        $this->baseUrl = $url;

        return $this;
    }

    /**
     * Set the path for the URL.
     */
    public function path(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Apply a pre-defined UTM preset from config.
     */
    public function preset(string $presetName): static
    {
        $presets = config('utm-builder.presets', []);

        if (isset($presets[$presetName])) {
            $this->utmParams = array_merge($this->utmParams, $presets[$presetName]);
        }

        return $this;
    }

    /**
     * Set UTM source.
     */
    public function source(string $source): static
    {
        $this->utmParams['utm_source'] = $this->sanitize($source);

        return $this;
    }

    /**
     * Set UTM medium.
     */
    public function medium(string $medium): static
    {
        $this->utmParams['utm_medium'] = $this->sanitize($medium);

        return $this;
    }

    /**
     * Set UTM campaign.
     */
    public function campaign(string $campaign): static
    {
        $this->utmParams['utm_campaign'] = $this->sanitize($campaign);

        return $this;
    }

    /**
     * Set UTM term.
     */
    public function term(string $term): static
    {
        $this->utmParams['utm_term'] = $this->sanitize($term);

        return $this;
    }

    /**
     * Set UTM content.
     */
    public function content(string $content): static
    {
        $this->utmParams['utm_content'] = $this->sanitize($content);

        return $this;
    }

    /**
     * Set UTM ID.
     */
    public function id(string $id): static
    {
        $this->utmParams['utm_id'] = $this->sanitize($id);

        return $this;
    }

    /**
     * Set all UTM parameters at once.
     */
    public function utm(array $params): static
    {
        $allowedKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_id'];

        foreach ($params as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                $this->utmParams[$key] = $this->sanitize($value);
            }
        }

        return $this;
    }

    /**
     * Add custom query parameters.
     */
    public function params(array $params): static
    {
        $this->queryParams = array_merge($this->queryParams, $params);

        return $this;
    }

    /**
     * Add a single query parameter.
     */
    public function param(string $key, mixed $value): static
    {
        $this->queryParams[$key] = $value;

        return $this;
    }

    /**
     * Add tracking reference with prefix.
     */
    public function ref(string $key, mixed $value): static
    {
        $prefix = config('utm-builder.ref_prefix', 'ref_');
        $this->queryParams[$prefix . $key] = $value;

        return $this;
    }

    /**
     * Clear all UTM parameters.
     */
    public function clearUtm(): static
    {
        $this->utmParams = [];

        return $this;
    }

    /**
     * Clear all query parameters.
     */
    public function clearParams(): static
    {
        $this->queryParams = [];

        return $this;
    }

    /**
     * Clear everything and start fresh.
     */
    public function reset(): static
    {
        $this->path = '';
        $this->utmParams = [];
        $this->queryParams = [];

        return $this;
    }

    /**
     * Build the final URL.
     */
    public function build(): string
    {
        $url = rtrim($this->baseUrl, '/');

        if ($this->path) {
            $url .= '/' . ltrim($this->path, '/');
        }

        $allParams = array_merge($this->utmParams, $this->queryParams);

        if (empty($allParams)) {
            return $url;
        }

        // Handle existing query string in URL
        $separator = Str::contains($url, '?') ? '&' : '?';

        return $url . $separator . http_build_query($allParams);
    }

    /**
     * Get the URL as string (alias for build).
     */
    public function get(): string
    {
        return $this->build();
    }

    /**
     * Get URL and data as array.
     */
    public function toArray(): array
    {
        return [
            'url' => $this->build(),
            'base_url' => $this->baseUrl,
            'path' => $this->path,
            'utm_params' => $this->utmParams,
            'query_params' => $this->queryParams,
        ];
    }

    /**
     * Get only UTM parameters.
     */
    public function getUtmParams(): array
    {
        return $this->utmParams;
    }

    /**
     * Get only query parameters.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Get all parameters combined.
     */
    public function getAllParams(): array
    {
        return array_merge($this->utmParams, $this->queryParams);
    }

    /**
     * Check if UTM parameters are set.
     */
    public function hasUtm(): bool
    {
        return ! empty($this->utmParams);
    }

    /**
     * Convert to JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Sanitize parameter value.
     */
    protected function sanitize(string $value): string
    {
        if (config('utm-builder.lowercase', true)) {
            $value = Str::lower($value);
        }

        if (config('utm-builder.replace_spaces', true)) {
            $replacement = config('utm-builder.space_replacement', '_');
            $value = str_replace(' ', $replacement, $value);
        }

        return $value;
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->build();
    }
}
