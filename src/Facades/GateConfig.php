<?php

namespace OEngine\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void setFields($fields = [])
 * @method static void setForm($form)
 * @method static void setModel(string $model)
 * @method static void setModelKey(string $key)
 * @method static void setTitle(string $title)
 * @method static void disableModule(bool $flg)
 * @method static void setFuncQuery(callable $callback)
 * @method static \OEngine\Core\Support\Config\FieldConfig Field($field='')
 * @method static \OEngine\Core\Support\Config\FieldConfig FieldStatus($field = 'status',$model='user',$modelKey='id')
 * @method static \OEngine\Core\Support\Config\FormConfig Form()
 * @method static \OEngine\Core\Support\Config\ButtonConfig Button($title = '')
 * @method static \OEngine\Core\Support\Config\OptionConfig Option($title = '')
 * @method static \OEngine\Core\Support\Config\WidgetConfig Widget($title = '')
 * @method static \OEngine\Core\Support\Config\ConfigManager NewItem($title='')
 * 
 * 
 * @see \OEngine\Core\Facades\GateConfig
 */
class GateConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Core\Support\Config\ConfigManager::class;
    }
}
