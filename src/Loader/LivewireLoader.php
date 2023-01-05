<?php

namespace OEngine\Core\Loader;

use Illuminate\Support\Str;
use OEngine\Core\Facades\Core;
use OEngine\Core\Livewire\Contracts\SkipLoad;
use OEngine\Core\Livewire\Widget;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;

class LivewireLoader
{

    public static function RegisterWidget($path, $namespace, $aliasPrefix = '')
    {
        $aliasPrefix = 'widget-' . $aliasPrefix;

        Core::AllClassFile(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix) {
                $alias = $aliasPrefix . Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');
                // fix class namespace
                $alias_class = trim(Str::of($class)
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.'), '.');
                if (Str::endsWith($class, ['\Index', '\index'])) {
                    Core::registerWidget(Str::beforeLast($alias, '.index'));
                    Livewire::component(Str::beforeLast($alias, '.index'), $class);
                    Livewire::component(Str::beforeLast($alias_class, '.index'), $class);
                } else {
                    Core::registerWidget($alias);
                }
                //Core::RegisterWidget($alias_class);
                Livewire::component($alias_class, $class);
                Livewire::component($alias, $class);
            },
            function ($class) {
                if (!class_exists($class)) return false;
                $refClass = new ReflectionClass($class);
                return $refClass && !$refClass->isAbstract() && !$refClass->implementsInterface(SkipLoad::class) && $refClass->isSubclassOf(Widget::class);
            }
        );
    }
    public static function Register($path, $namespace, $aliasPrefix = '')
    {
        Core::AllClassFile(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix) {
                $alias = $aliasPrefix . Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');
                // fix class namespace
                $alias_class = trim(Str::of($class)
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.'), '.');
                if (Str::endsWith($class, ['\Index', '\index'])) {
                    Livewire::component(Str::beforeLast($alias, '.index'), $class);
                    Livewire::component(Str::beforeLast($alias_class, '.index'), $class);
                }
                Livewire::component($alias_class, $class);
                Livewire::component($alias, $class);
            },
            function ($class) {
                if (!class_exists($class)) return false;
                $refClass = new ReflectionClass($class);
                return  $refClass && !$refClass->isAbstract() && !$refClass->implementsInterface(SkipLoad::class) && $refClass->isSubclassOf(Component::class);
            }
        );
    }
}
