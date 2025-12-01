# Laravel UTM Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/samer-alshaer/laravel-utm-builder.svg?style=flat-square)](https://packagist.org/packages/samer-alshaer/laravel-utm-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/samer-alshaer/laravel-utm-builder.svg?style=flat-square)](https://packagist.org/packages/samer-alshaer/laravel-utm-builder)
[![License](https://img.shields.io/packagist/l/samer-alshaer/laravel-utm-builder.svg?style=flat-square)](https://packagist.org/packages/samer-alshaer/laravel-utm-builder)

A professional Laravel package for generating URLs with UTM parameters. Features a fluent API, presets, helpers, Blade directives, and full Laravel integration.

## Features

- 🔗 **Fluent API** - Chainable methods for building URLs
- 📦 **Presets** - Pre-defined UTM configurations for common use cases
- 🛠 **Helper Functions** - Quick URL generation anywhere in your code
- 🎨 **Blade Directives** - Easy use in your views
- 🏷 **Model Trait** - Add UTM link generation to your Eloquent models
- ⚙️ **Configurable** - Customize everything via config file
- ✅ **Fully Tested** - Comprehensive test coverage
- 📱 **Laravel 10, 11 & 12** - Support for latest Laravel versions

## Installation

Install the package via Composer:

```bash
composer require samer-alshaer/laravel-utm-builder
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="utm-builder-config"
```

## Configuration

Add these optional environment variables to your `.env` file:

```env
UTM_BASE_URL=https://your-app.com
UTM_CLIENT_URL=https://your-client-site.com
```

## Usage

### Using the Fluent Builder

```php
use Samer\UtmBuilder\UtmBuilder;

// Basic usage
$url = UtmBuilder::make()
    ->path('landing-page')
    ->source('google')
    ->medium('cpc')
    ->campaign('summer_sale')
    ->build();
// https://your-app.com/landing-page?utm_source=google&utm_medium=cpc&utm_campaign=summer_sale

// Using presets
$url = UtmBuilder::make()
    ->path('signup')
    ->preset('newsletter')
    ->content('header_button')
    ->build();

// With additional parameters
$url = UtmBuilder::make()
    ->path('product/123')
    ->preset('email')
    ->ref('user', 456)
    ->param('discount', '20OFF')
    ->build();
```

### Using the Facade

```php
use Samer\UtmBuilder\Facades\Utm;

$url = Utm::make()
    ->path('checkout')
    ->preset('email')
    ->campaign('abandoned_cart')
    ->build();
```

### Using Helper Functions

```php
// Quick link with preset
$url = utm_link('page/path', 'email');

// Link with custom parameters
$url = utm_link('signup', 'newsletter', ['ref_id' => 123]);

// Client website URL
$url = utm_client('landing-page', 'facebook');

// Custom UTM parameters
$url = utm_url('https://example.com/page', [
    'utm_source' => 'partner',
    'utm_medium' => 'referral',
]);
```

### In Blade Templates

```blade
{{-- Using directive --}}
<a href="@utm('signup', 'newsletter')">Sign Up</a>

{{-- Using client directive --}}
<a href="@utmClient('landing', 'facebook')">Visit Site</a>

{{-- Using helper --}}
<a href="{{ utm_link('page', 'email') }}">Click Here</a>
```

### In Eloquent Models

```php
use Samer\UtmBuilder\Traits\HasUtmLinks;

class Product extends Model
{
    use HasUtmLinks;

    public function getShareLink(): string
    {
        return $this->utmBuilder()
            ->path("product/{$this->slug}")
            ->preset('whatsapp')
            ->ref('product', $this->id)
            ->build();
    }

    // Or use the built-in method
    public function getLink(): string
    {
        return $this->toUtmLink('email', ['discount' => '10OFF']);
    }

    // Customize the path
    protected function getUtmPath(): string
    {
        return "products/{$this->slug}";
    }
}
```

### Available Methods

| Method                | Description                     |
| --------------------- | ------------------------------- |
| `make($baseUrl)`      | Create new builder instance     |
| `client()`            | Create instance with client URL |
| `path($path)`         | Set URL path                    |
| `preset($name)`       | Apply preset configuration      |
| `source($source)`     | Set utm_source                  |
| `medium($medium)`     | Set utm_medium                  |
| `campaign($campaign)` | Set utm_campaign                |
| `term($term)`         | Set utm_term                    |
| `content($content)`   | Set utm_content                 |
| `id($id)`             | Set utm_id                      |
| `utm($array)`         | Set multiple UTM params         |
| `params($array)`      | Add query parameters            |
| `param($key, $value)` | Add single parameter            |
| `ref($key, $value)`   | Add reference tracking          |
| `build()`             | Generate the URL                |
| `toArray()`           | Get URL and params as array     |
| `getUtmParams()`      | Get UTM parameters only         |

## Presets

The package comes with several built-in presets:

```php
// Email
'email', 'newsletter', 'transactional'

// SMS
'sms', 'sms_notification', 'sms_promotion'

// Social Media
'facebook', 'twitter', 'linkedin', 'instagram', 'whatsapp'

// Advertising
'google_ads', 'facebook_ads'

// Other
'referral', 'affiliate', 'internal', 'admin_panel'
```

### Custom Presets

Add your own presets in `config/utm-builder.php`:

```php
'presets' => [
    'booking_payment' => [
        'utm_source' => 'system',
        'utm_medium' => 'email',
        'utm_campaign' => 'booking_payment',
    ],

    'agent_registration' => [
        'utm_source' => 'admin_panel',
        'utm_medium' => 'referral',
        'utm_campaign' => 'agent_signup',
    ],
],
```

## DataTables Example

```php
return DataTables::of($query)
    ->addColumn('link', function ($data) {
        $link = utm_client("agent/{$data->uuid}", 'agent_registration', [
            'ref_agent' => $data->id,
        ]);

        return '<button class="btn btn-sm copy-btn" data-link="' . $link . '">
            <i class="fa fa-copy"></i> Copy
        </button>';
    })
    ->rawColumns(['link'])
    ->make(true);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email your-email@example.com instead of using the issue tracker.

## Credits

- [Samer](https://github.com/samer-alshaer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
