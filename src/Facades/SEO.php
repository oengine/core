<?php

namespace OEngine\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static mixed ResgiterMeta(string|array $Class)
 * @method static mixed ResgiterSiteMap(string|array $Class)
 *
 * @see \OEngine\Core\Facades\SEO
 */
class SEO extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Core\Support\Seo\SeoManager::class;
    }
}
