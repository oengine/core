<?php

namespace OEngine\Core\Support\Core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use OEngine\Core\Facades\Module;
use OEngine\Core\Facades\Plugin;
use OEngine\Core\Facades\Theme;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Livewire\LifecycleManager;
use ReflectionClass;

class CoreManager
{
    private $app;
    private $filesystem;

    public function __construct(
        \Illuminate\Contracts\Foundation\Application $app,
        \Illuminate\Filesystem\Filesystem $filesystem
    ) {
        $this->app = $app;
        $this->filesystem = $filesystem;
    }
    private $user;
    public function user()
    {
        return $this->user ?? ($this->user = request()->user());
    }
    public function checkPermission($per = '')
    {
        return apply_filters("core_check_permission", ($per == '' || Gate::check($per, [$this->user()])), $per);
    }
    /**
     * Setup an after resolving listener, or fire immediately if already resolved.
     *
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public function callAfterResolving($name, $callback)
    {
        $this->app->afterResolving($name, $callback);

        if ($this->app->resolved($name)) {
            $callback($this->app->make($name), $this->app);
        }
    }

    public function loadViewsFrom($path, $namespace = 'core')
    {
        $this->callAfterResolving('view', function ($view) use ($path, $namespace) {
            if (
                isset($this->app->config['view']['paths']) &&
                is_array($this->app->config['view']['paths'])
            ) {
                foreach ($this->app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                        $view->addNamespace($namespace, $appPath);
                    }
                }
            }

            $view->addNamespace($namespace, $path);
        });
    }
    public function RoleAdmin()
    {
        return config('core.permission.role', 'admin');
    }
    public function adminPrefix()
    {
        return apply_filters("router_admin_prefix", config('core.web.admin', '/admincp'));
    }
    public function MapPermissionModule($arr)
    {
        if (is_array($arr)) {
            if ($arr['name'] == 'core.table.slug' && ($module = getValueByKey($arr, 'param.module', '')) != '') {
                return 'core.' . $module;
            }
            return $arr['name'];
        }
        return $arr;
    }
    private const KeyLanguage = 'language_current';
    public function SwitchLanguage($lang, $redirect_current = false)
    {
        Session::put(self::KeyLanguage, $lang);
        if ($redirect_current)
            return Redirect::to(URL::current());
    }
    public function checkCurrentLanguage()
    {
        // current uri language ($lang_uri)
        $lang_uri = Request::segment(1);
        $languages = array_keys(apply_filters('language_list', []));
        // Set default session language if none is set
        if (!Session::has(self::KeyLanguage)) {
            // use lang in uri, if provided
            if (in_array($lang_uri, $languages)) {
                $lang = $lang_uri;
            }
            // detect browser language
            elseif (Request::server('http_accept_language')) {
                $headerlang = substr(Request::server('http_accept_language'), 0, 2);

                if (in_array($headerlang, $languages)) {
                    // browser lang is supported, use it
                    $lang = $headerlang;
                }
                // use default application lang
                else {
                    $lang = Config::get('app.locale');
                }
            }
            // no lang in uri nor in browser. use default
            else {
                // use default application lang
                $lang = Config::get('app.locale');
            }

            // set application language for that user
            Session::put(self::KeyLanguage, $lang);
        }
        // session is available

        $lang_uri = Session::get(self::KeyLanguage);
        // prefix is missing? add it
        if (!in_array($lang_uri, $languages)) {
            return Redirect::to(URL::current());
        }
        // a valid prefix is there, but not the correct lang? change app lang
        elseif (in_array($lang_uri, $languages)) {
            app()->setLocale($lang_uri);
        }
    }
    public function RootPath($path = '')
    {
        return base_path(config('core.appdir.root') . '/' . $path);
    }
    public function ThemePath($path = '')
    {
        return $this->PathBy('theme', $path);
    }
    public function PluginPath($path = '')
    {
        return $this->PathBy('plugin', $path);
    }
    public function ModulePath($path = '')
    {
        return $this->PathBy('module', $path);
    }
    public function PathBy($name, $path = '')
    {
        return $this->RootPath(config('core.appdir.' . $name) . '/' . $path);
    }
    public function LoadHelper($path)
    {
        if ($path && $this->FileExists($path)) {
            require_once $path;
            return true;
        }
        return false;
    }

    public function RegisterAllFile($path)
    {
        $this->AllFile($path, function (SplFileInfo $file) {
            self::LoadHelper($file->getPathname());
        });
    }
    public function minifyOptimizeHtml($buffer)
    {
        if (strpos($buffer, '<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"         => '<?php ',
                "/\r/"           => '',
                "/>\n</"          => '><',
                "/>\s+\n</"         => '><',
                "/>\n\s+</"         => '><',
                '/\>\s+/s'         => '> ',
                '/\s+</s'          => ' <',
            );
        } else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"         => '<?php ',
                "/\n([\S])/"        => '$1',
                "/\r/"           => '',
                "/\n/"           => '',
                "/\t/"           => '',
                "/ +/"           => ' ',
                '/\>\s+/s'         => '> ',
                '/\s+</s'          => ' <',
            );
        }
        return preg_replace(array_keys($replace), array_values($replace), $buffer);
    }
    public function By($name)
    {
        if ($name == Theme::getName()) {
            return Theme::getFacadeRoot();
        }
        if ($name == Module::getName()) {
            return Module::getFacadeRoot();
        }
        if ($name == Plugin::getName()) {
            return Plugin::getFacadeRoot();
        }
    }
    public function checkFolder($remove = false)
    {
        $arr = [config('core.appdir.theme', 'themes'), config('core.appdir.module', 'modules'), config('core.appdir.plugin', 'plugins')];
        $root_path = config('core.appdir.root', 'GateApp');
        foreach ($arr as $item) {
            $public = Str::lower(public_path($item));
            $appdir = Str::lower(base_path($root_path . '/' . $item));
            if ($remove) {
                $this->filesystem->deleteDirectory($public);
            }
            $this->filesystem->ensureDirectoryExists($public);
            $this->filesystem->ensureDirectoryExists($appdir);
        }
    }

    public function FileExists($path)
    {
        return $this->filesystem->exists($path);
    }

    public function SaveFileJson($path, $content)
    {
        return file_put_contents($path, json_encode($content));
    }
    public function FileJson($path)
    {
        if (!$this->FileExists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true);
    }
    public function FileReturn($path)
    {
        return include_once $path;
    }

    public function AllFile($directory, $callback = null, $filter = null)
    {
        if (!$this->filesystem->isDirectory($directory)) {
            return false;
        }
        if ($callback) {
            if ($filter) {
                collect($this->filesystem->allFiles($directory))->filter($filter)->each($callback);
            } else {
                collect($this->filesystem->allFiles($directory))->each($callback);
            }
        } else {
            return $this->filesystem->allFiles($directory);
        }
    }

    public function AllClassFile($directory, $namespace, $callback = null, $filter = null)
    {
        $files = self::AllFile($directory);
        if (!$files || !is_array($files)) return [];

        $classList = collect($files)->map(function (SplFileInfo $file) use ($namespace) {
            return (string) Str::of($namespace)
                ->append('\\', $file->getRelativePathname())
                ->replace(['/', '.php'], ['\\', '']);
        });
        if ($callback) {
            if ($filter) {
                $classList = $classList->filter($filter);
            }
            $classList->each($callback);
        } else {
            return $classList;
        }
    }

    public function AllFolder($directory, $callback = null, $filter = null)
    {
        if (!$this->filesystem->isDirectory($directory)) {
            return false;
        }
        if ($callback) {
            if ($filter) {
                collect($this->filesystem->directories($directory))->filter($filter)->each($callback);
            } else {
                collect($this->filesystem->directories($directory))->each($callback);
            }
        } else {
            return $this->filesystem->directories($directory);
        }
    }
    public function resetLinks()
    {
        return $this->arrLink = collect($this->arrLink)->where(function ($item) {
            return $item['lockLink'] == true;
        })->toArray();
    }
    public function getLinks()
    {
        return $this->arrLink;
    }
    private $lockLink = true;
    public function UnLockLink()
    {
        $this->lockLink = false;
    }
    private $arrLink = [];
    public function Link($target, $link, $relative = false, $force = true)
    {
        $this->arrLink[$target . $link] = [
            'target' => $target,
            'link' => $link,
            'relative' => $relative,
            'force' => $force,
            'lockLink' => $this->lockLink
        ];
    }
    public function getPathDirFromClass($class)
    {
        $reflector = new ReflectionClass(get_class($class));

        return dirname($reflector->getFileName());
    }
    public function getPermissionGuest()
    {
        return apply_filters('core_auth_permission_guest', config('core.permission.guest') ?? []);
    }
    public function getPermissionCustom()
    {
        return apply_filters('core_auth_permission_custom', config('core.permission.custom') ?? []);
    }
    public function Livewire($name, $params = [])
    {
        // This is if a user doesn't pass params, BUT passes key() as the second argument.
        if (is_string($params)) $params = [];

        $id = str()->random(20);

        if (class_exists($name)) {
            $name = $name::getName();
        }

        return LifecycleManager::fromInitialRequest($name, $id)
            ->boot()
            ->initialHydrate()
            ->mount($params);
    }
    private $widgets = [];
    public function getWidgets()
    {
        return $this->widgets;
    }
    public function registerWidget($path)
    {
        $this->widgets[$path] = $path;
    }
    public function mereArr($arr1, $arr2)
    {
        if ($arr1 == null) $arr1 = [];
        if ($arr2 == null) $arr2 = [];

        if (is_array($arr2)) {
            foreach (array_keys($arr2) as $key) {
                $arr1[$key] = $arr2[$key];
            }
        }

        return $arr1;
    }
    public function base64Encode($text)
    {
        return base64_encode(urlencode($text));
    }
    public function base64Decode($hash)
    {
        return urldecode(base64_decode($hash));
    }
    public function jsonDecode($hash)
    {
        return json_decode(str_replace("'", '"', str_replace('"', "\\\"", $hash)), true);
    }
    public function reModuleLink()
    {

        Artisan::call('module:link', ['--reload' => 1]);
        // $process = new Process(["php artisan module:link"]);
        // $process->start();
    }
    private $modelOrInfoSeo = null;
    public function setModelSeo($modelOrInfoSeo)
    {
        $this->modelOrInfoSeo = $modelOrInfoSeo;
    }
    public function getModelSeo()
    {
        return $this->modelOrInfoSeo;
    }
}
