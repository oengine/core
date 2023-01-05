<?php

namespace OEngine\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void callAfterResolving(string $name,callable $callback)
 * @method static void loadViewsFrom(string $path,string $namespace)
 * @method static string RoleAdmin()
 * @method static string adminPrefix()
 * @method static void MapPermissionModule(array $arr)
 * @method static void SwitchLanguage(string $lang, bool $redirect_current)
 * @method static string checkCurrentLanguage()
 * @method static string RootPath(string $path)
 * @method static string ThemePath(string $path)
 * @method static string PluginPath(string $path)
 * @method static string ModulePath(string $path)
 * @method static string PathBy(string $path)
 * @method static bool LoadHelper(string $path)
 * @method static void RegisterAllFile(string $path)
 * @method static void minifyOptimizeHtml(string $buffer)
 * @method static mixed By(string $name)
 * @method static string checkFolder($remove=false)
 * @method static bool FileExists(string $path)
 * @method static bool SaveFileJson(string $path,string $content)
 * @method static string FileJson(string $path)
 * @method static string FileReturn(string $path)
 * @method static mixed AllFile(string $path,callable $callback = null,callable $filter = null)
 * @method static mixed AllClassFile(string $path,string $namespace,callable $callback = null,callable $filter = null)
 * @method static mixed AllFolder(string $path,callable $callback = null,callable $filter = null)
 * @method static mixed Link(string $target,string $link,bool $relative = false,bool $force = true)
 * @method static mixed getPermissionGuest()
 * @method static mixed getPermissionCustom()
 * @method static mixed getWidgets()
 * @method static string getPathDirFromClass(mix $class)
 * @method static void registerWidget(string $path)
 * 
 * @method static \OEngine\Core\Models\User user()
 * @method static bool checkPermission(string $per)
 * @method static void Livewire($name, $params = [])
 * @method static void mereArr($arr1=[],$arr2=[])
 * @method static mixed getLinks()
 * @method static void resetLinks()
 * @method static void UnLockLink();
 * @method static string base64Encode(string $text)
 * @method static string base64Decode(string $hash)
 * @method static string jsonDecode(string $hash)
 * 
 * 
 * @see \OEngine\Core\Facades\Core
 */
class Core extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Core\Support\Core\CoreManager::class;
    }
}
