<?php

namespace OEngine\Core\Traits;

use OEngine\Core\Facades\Core;
use OEngine\Core\Facades\Theme;
use OEngine\Core\Loader\DashboardLoader;
use OEngine\Core\Loader\LivewireLoader;
use OEngine\Core\Loader\OptionLoader;
use OEngine\Core\Loader\TableLoader;
use Illuminate\Support\Facades\Route;
use OEngine\LaravelPackage\WithServiceProvider as WithServiceProviderBase;

trait WithServiceProvider
{
    use WithServiceProviderBase {
        register as protected registerBase;
        boot as protected bootBase;
    }
    public function register()
    {
        $this->ExtendPackage();
        $this->registerBase();
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

        Theme::Load($this->package->basePath('/../themes'));
        DashboardLoader::load($this->package->basePath('/../config/dashboards'), $this->package->shortName() . '::');
        TableLoader::load($this->package->basePath('/../config/tables'));
        OptionLoader::load($this->package->basePath('/../config/options'));
        $this->packageRegistered();
        return $this;
    }


    public function boot()
    {
        $this->bootBase();
        if (class_exists('\\Livewire\\Component')) {
            LivewireLoader::Register($this->package->basePath('/Http/Livewire'), $this->getNamespaceName() . '\\Http\\Livewire', $this->package->shortName() . '::');
            LivewireLoader::RegisterWidget($this->package->basePath('/../widgets'), $this->getNamespaceName() . '\\Widget', $this->package->shortName() . '::');
        }
        if ($this->app->runningInConsole()) {
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
        }

        if (Core::FileExists($this->package->basePath('/../routes/api.php')))
            Route::middleware('api')
                ->prefix('api')
                ->group($this->package->basePath('/../routes/api.php'));

        if (Core::FileExists($this->package->basePath('/../routes/web.php')))
            Route::middleware('web', \OEngine\Core\Http\Middleware\CoreMiddleware::class)
                ->group($this->package->basePath('/../routes/web.php'));

        if (Core::FileExists($this->package->basePath('/../routes/admin.php')))
            Route::middleware('web', \OEngine\Core\Http\Middleware\Authenticate::class, \OEngine\Core\Http\Middleware\CoreMiddleware::class)
                ->prefix(Core::adminPrefix())
                ->group($this->package->basePath('/../routes/admin.php'));


        $this->packageBooted();

        return $this;
    }
}
