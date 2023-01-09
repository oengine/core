<?php

namespace OEngine\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static mixed getParamByDelimiters(string $slug,array $delimiters,bool $format_key_value)
 * @method static mixed ViewBySlug(string $slug)
 * @method static array getParameters()
 *
 * @see \OEngine\Core\Facades\Slug
 */
class Slug extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Core\Support\Slug\SlugManager::class;
    }
}
