<?php

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Livewire\Modal;
use OEngine\Core\Models\Role;
use OEngine\Core\Support\Config\ButtonConfig;

return GateConfig::NewItem()
    ->setModel(\OEngine\Core\Models\Role::class)
    ->setButtonAppend([
        GateConfig::Button('core::tables.role.button.permission')
            ->setIcon('<i class="bi bi-magic"></i>')
            ->setClass('btn btn-primary btn-sm')
            ->setPermission('core.role.permission')
            ->setDoComponent('core::page.permission.role', function ($id) {
                return "{'roleId':" . $id . "}";
            })
            ->setType(ButtonConfig::TYPE_UPDATE)
    ])
    ->setForm(GateConfig::Form()->setSize(Modal::Large))
    ->setFields([
        GateConfig::Field('slug')
            ->setTitle('core::tables.role.field.slug')
            ->disableEdit(),

        GateConfig::Field('name')
            ->setTitle('core::tables.role.field.name')
    ]);
