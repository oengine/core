<?php

namespace OEngine\Core;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use OEngine\Core\Facades\Theme;
use OEngine\Core\Support\Core\ServicePackage;
use OEngine\Core\Traits\WithServiceProvider;
use OEngine\Core\Builder\Menu\MenuBuilder;
use OEngine\Core\Facades\Core;
use OEngine\Core\Facades\Module;
use OEngine\Core\Facades\Plugin;
use OEngine\Core\TagCompiler\LivewireGTagCompiler;
use OEngine\Core\TagCompiler\WidgetTagCompiler;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class CoreServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('core')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations()
            ->runsSeeds();
    }
    public function extending()
    {

        // First Setup Layout Theme
        add_action("theme_head_before", function ($isPageTitle = true) {
            if (!isset($isPageTitle) || $isPageTitle === true) {
                echo "<title>" . page_title() . "</title>";
            }
            Theme::getAssets()->loadAsset("asset_head_before");
        }, 0);
        add_action("theme_head_after", function () {
            Theme::getAssets()->loadAsset("asset_head_after");
        }, 0);

        add_action("theme_body_before", function () {
            Theme::getAssets()->loadAsset("asset_body_before");
        }, 0);
        add_action("theme_body_after", function () {
            Theme::getAssets()->loadAsset("asset_body_after");
        }, 0);
        add_filter('page_body_class', function ($prev) {
            return $prev . (session('admin_sidebar_mini') ? ' is-sidebar-mini ' : '');
        });
        add_filter('language_list', function ($prev) {

            return Core::mereArr($prev, [
                'en' => 'us',
                'vi' => 'vn',
                'jp' => 'jp'
            ]);
        });
        add_filter('core_auth_filter_gate', function ($prev, $user, $permission) {
            if ($permission->slug == 'core.option') {
                return false;
            }
            return $prev;
        }, 20, 3);
        add_filter('core_check_permission', function ($prev, $slug) {
            if (!auth()->user()->isSuperAdmin() && in_array($slug, ['core.module', 'core.plugin', 'core.theme'])) {
                return false;
            }
            return $prev;
        }, 20, 2);
    }
    public function registerMenu()
    {

        add_menu_with_sub(function ($subItem) {
            $subItem
                ->addItem('core::menu.sidebar.user', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'user']], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.role', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'role']], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.permission', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'permission']], MenuBuilder::ItemRouter);
        }, 'core::menu.sidebar.user',  'bi bi-speedometer');

        add_menu_with_sub(function ($subItem) {
            $subItem
                ->addItem('core::menu.sidebar.data-list', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'data_list']], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.custom-field', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'custom_field_group']], MenuBuilder::ItemRouter);
        }, 'core::menu.sidebar.data-extend',  'bi bi-speedometer');
        add_menu_with_sub(function ($subItem) {
            $subItem->addItem('core::menu.sidebar.setting', 'bi bi-speedometer', '', ['name' => 'core.option', 'param' => []], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.module', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'module']], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.plugin', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'plugin']], MenuBuilder::ItemRouter)
                ->addItem('core::menu.sidebar.theme', 'bi bi-speedometer', '', ['name' => 'core.table.slug', 'param' => ['module' => 'theme']], MenuBuilder::ItemRouter);
        }, 'core::menu.sidebar.setting', 'bi bi-speedometer');
        add_menu_item('core::menu.sidebar.dashboard', 'bi bi-speedometer', '', 'core.dashboard', MenuBuilder::ItemRouter, '', '', -100);
    }
    protected function registerBladeDirectives()
    {
        Blade::directive('livewireG', [OEngineBladeDirectives::class, 'livewireG']);
        Blade::directive('endLivewireG', [OEngineBladeDirectives::class, 'endLivewireG']);
        Blade::directive('childSlot', [OEngineBladeDirectives::class, 'childSlot']);
        //Blade directives
        Blade::directive('role', function ($role) {
            return "if(auth()->check() &&(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('{$role}'))) :"; //return this if statement inside php tag
        });

        Blade::directive('endrole', function ($role) {
            return "endif;"; //return this endif statement inside php tag
        });
    }
    public function packageRegistered()
    {
        Theme::LoadApp();
        Module::LoadApp();
        Plugin::LoadApp();
        $this->registerMenu();
        $this->extending();
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {

            app(config('core.auth.permission', \OEngine\Core\Models\Permission::class))->get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user = null) use ($permission) {
                    if (!$user) $user = auth();
                    if ($user->isBlock()) return false;
                    if (!apply_filters('core_auth_filter_gate', true, $user, $permission) || !apply_filters('core_auth_filter_gate_admin', true, $user)) return false;
                    return $user->hasPermissionTo($permission) || $user->isSuperAdmin();
                });
            });
            Gate::before(function ($user, $ability) {
                if (!$user) $user = auth();
                if (apply_filters('core_auth_filter_gate_admin', true, $user) && $user->isSuperAdmin()) {
                    return true;
                }
            });
            foreach (Core::getPermissionGuest() as $item) {
                Gate::define($item, function () {
                    return true;
                });
            }
            $this->registerBladeDirectives();
            add_filter('core_auth_permission_custom', function ($prev) {
                return [
                    ...$prev,
                    "core.user.change-password",
                    "core.user.permission",
                    "core.role.permission"
                ];
            });
        }
    }

    protected function registerTagCompiler()
    {
        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            // $this->app['blade.compiler']->precompiler(function ($string) {
            //     foreach ([WidgetTagCompiler::class, LivewireGTagCompiler::class] as $item) {
            //         $string = app($item)->compile($string);
            //     }
            //     return  $string;
            // });
            $this->app['blade.compiler']->precompiler(function ($string) {
                return  app(WidgetTagCompiler::class)->compile($string);;
            });
            $this->app['blade.compiler']->precompiler(function ($string) {
                return  app(LivewireGTagCompiler::class)->compile($string);;
            });
        }
    }

    public function bootingPackage()
    {
        $this->registerTagCompiler();

        add_link_symbolic($this->package->basePath('/../public'), public_path('modules/oengine-core'));
        add_asset_js(asset('modules/oengine-core/js/oengine-core.js'), '', 0);
        add_asset_css(asset('modules/oengine-core/css/oengine-core.css'), '',  0);
        add_asset_css('https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css', 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css',  0);
        Core::UnLockLink();
        Module::RegisterApp();
        Plugin::RegisterApp();
        Livewire::component("oengine-layout", \OEngine\Core\Http\Livewire\Common\Layout\Index::class);
    }
    public function packageBooted()
    {
        //core.user.change-password
        Module::BootApp();
        Plugin::BootApp();
        $this->bootGate();
    }
}
