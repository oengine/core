<?php

namespace OEngine\Core\Commands;

use Illuminate\Console\Command;
use OEngine\Core\Facades\Core;

class CoreCommand extends Command
{
    public $signature = 'core-install';
    public $description = 'oengine\core install';
    protected bool $askToRunMigrations = true;
    protected bool $copyServiceProviderInApp = true;
    protected ?string $starRepo = "oengine/core";

    public function handle(): int
    {
        if ($this->askToRunMigrations) {
            if ($this->confirm('Would you like to run the migrations now?')) {
                $this->comment('Running migrations...');

                $this->call('migrate:fresh', [
                    '--seed',
                    '--seeder' => '\\OEngine\\Core\\Database\\Seeders\\CoreSeeder'
                ]);
            }
        }

        if ($this->copyServiceProviderInApp) {
            $this->copyServiceProviderInApp();
        }

        if ($this->starRepo) {
            if ($this->confirm('Would you like to star our repo on GitHub?')) {
                $repoUrl = "https://github.com/{$this->starRepo}";

                if (PHP_OS_FAMILY == 'Darwin') {
                    exec("open {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Windows') {
                    exec("start {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Linux') {
                    exec("xdg-open {$repoUrl}");
                }
            }
        }

        $this->info("OEngine\\Core has been installed!");
        $this->comment('All done');

        return self::SUCCESS;
    }

    protected function copyServiceProviderInApp(): self
    {
        
        ReplaceTextInFile(app_path('Models/User.php'), "Illuminate\\Foundation\\Auth\\User", "OEngine\\Core\\Models\\User", true, "OEngine\\Core\\Models\\User");
        $this->info("App/Models/User.php has been updated!");
        Core::checkFolder();
        $this->call('module:link');
        return $this;
    }
}
