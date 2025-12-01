# Changelog

All notable changes to `laravel-utm-builder` will be documented in this file.

## [1.0.1] - 2025-12-01

### Added

- Initial release
- Fluent API for building UTM URLs
- Pre-defined presets for common use cases
- Helper functions: `utm()`, `utm_link()`, `utm_client()`, `utm_url()`
- Blade directives: `@utm`, `@utmClient`
- `HasUtmLinks` trait for Eloquent models
- Facade support
- Full configuration options
- Support for Laravel 10, 11, and 12
- Comprehensive test suite
- Arabic and English documentation

### Changed

- Improved configuration handling for non-Laravel environments
- Added static defaults for testing outside Laravel context

### Fixed

- Fixed unit tests failing without Laravel application
