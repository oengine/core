<?php

namespace OEngine\Core\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use OEngine\Core\Exceptions\InvalidPackage;
use OEngine\Core\Facades\Core;
use OEngine\Core\Facades\Theme;
use OEngine\Core\Http\Middleware\Authenticate;
use OEngine\Core\Loader\DashboardLoader;
use OEngine\Core\Loader\LivewireLoader;
use OEngine\Core\Loader\OptionLoader;
use OEngine\Core\Loader\TableLoader;
use OEngine\Core\Support\Core\ServicePackage;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

trait WithServiceProvider
{
    protected ServicePackage $package;

    abstract public function configurePackage(ServicePackage $package): void;

    public function register()
    {
        $this->registeringPackage();

        $this->package = $this->newPackage();

        $this->package->setBasePath($this->getPackageBaseDir());

        $this->configurePackage($this->package);

        if (empty($this->package->name)) {
            throw InvalidPackage::nameIsRequired();
        }

        if ($this->package->hasHelpers) {
            Core::RegisterAllFile($this->package->basePath($this->package->pathHelper));
        }
        if (function_exists('add_filter')) {
            $name = $this->package->name;
            $path = $this->package->basePath('');
            add_filter('service_provider_register', function ($prev) use ($name, $path) {

                return Core::mereArr($prev, [
                    $name => $path
                ]);
            });
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $this->mergeConfigFrom($this->package->basePath("/../config/{$configFileName}.php"), $configFileName);
        }

        Theme::Load($this->package->basePath('/../themes'));
        DashboardLoader::load($this->package->basePath('/../config/dashboards'), $this->package->shortName() . '::');
        TableLoader::load($this->package->basePath('/../config/tables'));
        OptionLoader::load($this->package->basePath('/../config/options'));
        $this->packageRegistered();

        return $this;
    }

    public function newPackage(): ServicePackage
    {
        return new ServicePackage();
    }

    public function boot()
    {
        $this->bootingPackage();

        if (class_exists('\\Livewire\\Component')) {
            LivewireLoader::Register($this->package->basePath('/Http/Livewire'), $this->getNamespaceName() . '\\Http\\Livewire', $this->package->shortName() . '::');
            LivewireLoader::RegisterWidget($this->package->basePath('/../widgets'), $this->getNamespaceName() . '\\Widget', $this->package->shortName() . '::');
        }

        if ($this->package->hasTranslations) {
            $langPath = 'vendor/' . $this->package->shortName();

            $langPath = (function_exists('lang_path'))
                ? lang_path($langPath)
                : resource_path('lang/' . $langPath);
        }

        if ($this->app->runningInConsole()) {
            foreach ($this->package->configFileNames as $configFileName) {
                $this->publishes([
                    $this->package->basePath("/../config/{$configFileName}.php") => config_path("{$configFileName}.php"),
                ], "{$this->package->shortName()}-config");
            }

            if ($this->package->hasViews) {
                $this->publishes([
                    $this->package->basePath('/../resources/views') => base_path("resources/views/vendor/{$this->package->shortName()}"),
                ], "{$this->package->shortName()}-views");
            }

            $now = Carbon::now();
            foreach ($this->package->migrationFileNames as $migrationFileName) {
                $filePath = $this->package->basePath("/../database/migrations/{$migrationFileName}.php");
                if (!file_exists($filePath)) {
                    // Support for the .stub file extension
                    $filePath .= '.stub';
                }

                $this->publishes([
                    $filePath => $this->generateMigrationName(
                        $migrationFileName,
                        $now->addSecond()
                    ),
                ], "{$this->package->shortName()}-migrations");

                if ($this->package->runsMigrations) {
                    $this->loadMigrationsFrom($filePath);
                }
            }
            if ($this->package->runsMigrations) {
                $migrationFiles =  Core::AllFile($this->package->basePath("/../database/migrations/"));
                if ($migrationFiles && count($migrationFiles) > 0) {
                    foreach ($migrationFiles  as $file) {
                        if ($file->getExtension() == "php") {
                            $this->loadMigrationsFrom($file->getRealPath());
                        }
                    }
                }
            }

            if ($this->package->runsSeeds) {
                $seedFiles =  Core::AllFile($this->package->basePath("/../database/seeders/"));
                if ($seedFiles && count($seedFiles) > 0) {
                    foreach ($seedFiles  as $file) {
                        if ($file->getExtension() == "php") {
                            Core::LoadHelper($file->getRealPath());
                        }
                    }
                }
            }
            if ($this->package->hasTranslations) {
                $this->publishes([
                    $this->package->basePath('/../resources/lang') => $langPath,
                ], "{$this->package->shortName()}-translations");
            }

            if ($this->package->hasAssets) {
                $this->publishes([
                    $this->package->basePath('/../resources/dist') => public_path("vendor/{$this->package->shortName()}"),
                ], "{$this->package->shortName()}-assets");
            }
        }

        if (!empty($this->package->commands)) {
            $this->commands($this->package->commands);
        }
        if ($commands = config($this->package->shortName() . '.commands')) {
            if (is_array($commands) && count($commands) > 0) {
                $this->commands($commands);
            }
        }
        if ($this->package->hasTranslations) {
            $this->loadTranslationsFrom(
                $this->package->basePath('/../resources/lang/'),
                $this->package->shortName()
            );

            $this->loadJsonTranslationsFrom($this->package->basePath('/../resources/lang/'));

            $this->loadJsonTranslationsFrom($langPath);
        }

        if ($this->package->hasViews) {
            $this->loadViewsFrom($this->package->basePath('/../resources/views'), $this->package->viewNamespace());
        }

        foreach ($this->package->viewComponents as $componentClass => $prefix) {
            $this->loadViewComponentsAs($prefix, [$componentClass]);
        }

        if (count($this->package->viewComponents)) {
            $this->publishes([
                $this->package->basePath('/../Components') => base_path("app/View/Components/vendor/{$this->package->shortName()}"),
            ], "{$this->package->name}-components");
        }

        if ($this->package->publishableProviderName) {
            $this->publishes([
                $this->package->basePath("/../resources/stubs/{$this->package->publishableProviderName}.php.stub") => base_path("app/Providers/{$this->package->publishableProviderName}.php"),
            ], "{$this->package->shortName()}-provider");
        }
        if (Core::FileExists($this->package->basePath('/../routes/api.php')))
            Route::middleware('api')
                ->prefix('api')
                ->group($this->package->basePath('/../routes/api.php'));

        if (Core::FileExists($this->package->basePath('/../routes/web.php')))
            Route::middleware('web',\OEngine\Core\Http\Middleware\CoreMiddleware::class)
                ->group($this->package->basePath('/../routes/web.php'));

        if (Core::FileExists($this->package->basePath('/../routes/admin.php')))
            Route::middleware('web', \OEngine\Core\Http\Middleware\Authenticate::class,\OEngine\Core\Http\Middleware\CoreMiddleware::class)
                ->prefix(Core::adminPrefix())
                ->group($this->package->basePath('/../routes/admin.php'));
        foreach ($this->package->sharedViewData as $name => $value) {
            View::share($name, $value);
        }

        foreach ($this->package->viewComposers as $viewName => $viewComposer) {
            View::composer($viewName, $viewComposer);
        }

        $this->packageBooted();

        return $this;
    }

    public static function generateMigrationName(string $migrationFileName, Carbon $now): string
    {
        $migrationsPath = 'migrations/';

        $len = strlen($migrationFileName) + 4;

        if (Str::contains($migrationFileName, '/')) {
            $migrationsPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
        }

        foreach (glob(database_path("{$migrationsPath}*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName . '.php')) {
                return $filename;
            }
        }

        return database_path($migrationsPath . $now->format('Y_m_d_His') . '_' . Str::of($migrationFileName)->snake()->finish('.php'));
    }

    public function registeringPackage()
    {
    }

    public function packageRegistered()
    {
    }

    public function bootingPackage()
    {
    }

    public function packageBooted()
    {
    }

    protected function getPackageBaseDir(): string
    {
        return Core::getPathDirFromClass($this);
    }

    protected function getNamespaceName(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return $reflector->getNamespaceName();
    }
}
