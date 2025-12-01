<?php

declare(strict_types=1);

namespace Samer\UtmBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Samer\UtmBuilder\UtmBuilder make(?string $baseUrl = null)
 * @method static \Samer\UtmBuilder\UtmBuilder client()
 * @method static \Samer\UtmBuilder\UtmBuilder baseUrl(string $url)
 * @method static \Samer\UtmBuilder\UtmBuilder path(string $path)
 * @method static \Samer\UtmBuilder\UtmBuilder preset(string $presetName)
 * @method static \Samer\UtmBuilder\UtmBuilder source(string $source)
 * @method static \Samer\UtmBuilder\UtmBuilder medium(string $medium)
 * @method static \Samer\UtmBuilder\UtmBuilder campaign(string $campaign)
 * @method static \Samer\UtmBuilder\UtmBuilder term(string $term)
 * @method static \Samer\UtmBuilder\UtmBuilder content(string $content)
 * @method static \Samer\UtmBuilder\UtmBuilder id(string $id)
 * @method static \Samer\UtmBuilder\UtmBuilder utm(array $params)
 * @method static \Samer\UtmBuilder\UtmBuilder params(array $params)
 * @method static \Samer\UtmBuilder\UtmBuilder param(string $key, mixed $value)
 * @method static \Samer\UtmBuilder\UtmBuilder ref(string $key, mixed $value)
 * @method static \Samer\UtmBuilder\UtmBuilder clearUtm()
 * @method static \Samer\UtmBuilder\UtmBuilder clearParams()
 * @method static \Samer\UtmBuilder\UtmBuilder reset()
 * @method static string build()
 * @method static string get()
 * @method static array toArray()
 * @method static array getUtmParams()
 * @method static array getQueryParams()
 * @method static array getAllParams()
 * @method static bool hasUtm()
 * @method static string toJson()
 *
 * @see \Samer\UtmBuilder\UtmBuilder
 */
class Utm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'utm-builder';
    }
}
