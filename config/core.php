<?php

// config for OEngine\Core

use OEngine\Core\Commands\CoreCommand;
use OEngine\Core\Commands\ModuleDisableCommand;
use OEngine\Core\Commands\ModuleDumpCommand;
use OEngine\Core\Commands\ModuleEnableCommand;
use OEngine\Core\Commands\ModuleLinkCommand;
use OEngine\Core\Commands\PluginDumpCommand;
use OEngine\Core\Commands\ThemeDumpCommand;

return [
    'web' => [
        'admin' => '',
    ],
    'permission' => [
        'guest' => ['core.dashboard'],
        'custom' => [
            'core.user.permission',
            'core.role.permission',
            'core.permission.load-permission'
        ],
    ],
    'appdir' => [
        'root' => 'OApp',
        'theme' => 'Themes',
        'module' => 'Modules',
        'plugin' => 'Plugins',
    ],
    'commands' => [
        CoreCommand::class,
        ModuleDumpCommand::class,
        ModuleDisableCommand::class,
        ModuleEnableCommand::class,
        ModuleLinkCommand::class,
        ThemeDumpCommand::class,
        PluginDumpCommand::class
    ]
];
