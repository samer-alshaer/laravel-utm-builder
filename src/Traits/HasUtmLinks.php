<?php

declare(strict_types=1);

namespace Samer\UtmBuilder\Traits;

use Samer\UtmBuilder\UtmBuilder;

trait HasUtmLinks
{
    /**
     * Get a UTM builder instance for this model.
     */
    public function utmBuilder(?string $baseUrl = null): UtmBuilder
    {
        return UtmBuilder::make($baseUrl ?? config('utm-builder.client_url'));
    }

    /**
     * Get a UTM builder instance with client URL.
     */
    public function utmClientBuilder(): UtmBuilder
    {
        return UtmBuilder::client();
    }

    /**
     * Generate a UTM link for this model.
     *
     * @param  string  $preset  Preset name (optional)
     * @param  array  $extra  Additional parameters (optional)
     * @return string
     */
    public function toUtmLink(string $preset = '', array $extra = []): string
    {
        $builder = $this->utmBuilder()->path($this->getUtmPath());

        if ($preset) {
            $builder->preset($preset);
        }

        $builder->ref($this->getUtmRefKey(), $this->getUtmRefValue());

        if (! empty($extra)) {
            $builder->params($extra);
        }

        return $builder->build();
    }

    /**
     * Get the path segment for UTM URLs.
     * Override this method in your model.
     */
    protected function getUtmPath(): string
    {
        $modelName = strtolower(class_basename($this));
        $identifier = $this->uuid ?? $this->slug ?? $this->id;

        return "{$modelName}/{$identifier}";
    }

    /**
     * Get the reference key for tracking.
     * Override this method in your model.
     */
    protected function getUtmRefKey(): string
    {
        return strtolower(class_basename($this));
    }

    /**
     * Get the reference value for tracking.
     * Override this method in your model.
     */
    protected function getUtmRefValue(): mixed
    {
        return $this->id;
    }
}
