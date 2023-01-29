<?php

namespace OEngine\Core\Http\Action;

use OEngine\Core\Facades\Core;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use OEngine\Core\Loader\TableLoader;
use OEngine\Core\Support\Core\ActionBase;
use Illuminate\Support\Str;

class LoadPermission extends ActionBase
{
    public function DoAction()
    {
        $this->component->refreshData();
        $this->component->showMessage("LoadPermission");
        self::UpdatePermission();
    }
    private const routerExcept = [
        'sanctum.',
        'login',
        'register',
        'ignition.',
        'livewire.',
        'core.table.slug',
        'core.dashboard',
    ];
    private static $permisisonCode = [];
    public static function SetPermission($name, $router = null)
    {
        $check = false;
        foreach (self::routerExcept as $r) {
            if (str_contains($name, $r)) {
                $check = true;
                break;
            }
        }
        if ($check) return;
        $arrCode = [$name];
        if ($router != null && ((is_numeric($router) && $router == 1) || (!str_contains($router->action['controller'], '@') && in_array(WithTableIndex::class, class_uses_recursive($router->action['controller']))))) {
            array_push($arrCode, "{$name}.add");
            array_push($arrCode, "{$name}.edit");
            array_push($arrCode, "{$name}.remove");
            array_push($arrCode, "{$name}.inport");
            array_push($arrCode, "{$name}.export");
        }
        foreach ($arrCode as $code) {
            self::$permisisonCode[] = $code;
            if (!config('core.auth.permission', \OEngine\Core\Models\Permission::class)::where('slug', $code)->exists()) {
                config('core.auth.permission', \OEngine\Core\Models\Permission::class)::create([
                    'name' => $code,
                    'slug' => $code,
                    'group' => 'core'
                ]);
            }
        }
    }
    public static function UpdatePermission()
    {
        $routeCollection = Route::getRoutes();
        self::$permisisonCode = [];

        foreach ($routeCollection as $value) {
            $name = $value->getName();
            //skip with prexfix '_'
            if (!$name || Str::startsWith($name , '_')|| !in_array(Illuminate\Auth\Middleware\Authenticate::class, $value->gatherMiddleware())) continue;
            self::SetPermission($name, $value);
        }
        $table = TableLoader::getData();
        foreach ($table as $key => $value) {
            self::SetPermission('core.' . $key, 1);
        }
        $temp = Core::getPermissionCustom();
        if ($temp != null) {
            foreach ($temp as $key) {
                self::SetPermission($key);
            }
        }
        // foreach (module_all() as $module) {
        //     foreach ($module->getPermission() as $key) {
        //         self::SetPermission($key);
        //     }
        // }
        config('core.auth.permission', \OEngine\Core\Models\Permission::class)::query()->whereNotIn('slug', self::$permisisonCode)->delete();
        self::$permisisonCode = [];
    }
}
