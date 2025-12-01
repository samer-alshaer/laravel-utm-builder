<?php

declare(strict_types=1);

namespace Samer\UtmBuilder;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class UtmBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/utm-builder.php',
            'utm-builder'
        );

        $this->app->bind('utm-builder', function () {
            return new UtmBuilder();
        });

        $this->app->alias('utm-builder', UtmBuilder::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->registerBladeDirectives();
    }

    /**
     * Publish configuration file.
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/utm-builder.php' => config_path('utm-builder.php'),
            ], 'utm-builder-config');
        }
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @utm('path', 'preset')
        Blade::directive('utm', function ($expression) {
            return "<?php echo utm_link($expression); ?>";
        });

        // @utmClient('path', 'preset')
        Blade::directive('utmClient', function ($expression) {
            return "<?php echo utm_client($expression); ?>";
        });
    }
}
