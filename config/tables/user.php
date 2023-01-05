<?php

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Facades\Core;
use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Livewire\Modal;
use OEngine\Core\Support\Config\ButtonConfig;

return GateConfig::NewItem()
    ->setModel(\OEngine\Core\Models\User::class)
    ->setButtonAppend([
        GateConfig::Button('core::tables.user.button.permission')
            ->setIcon('<i class="bi bi-magic"></i>')
            ->setClass('btn btn-primary btn-sm')
            ->setPermission('core.user.permission')
            ->setDoComponent('core::page.permission.user', function ($id) {
                return "{'userId':" . $id . "}";
            })
            ->setType(ButtonConfig::TYPE_UPDATE)
    ])
    ->setForm(
        GateConfig::Form()->setSize(Modal::ExtraLarge)
            ->setRule(function () {
                return [
                    'name' => ['required'],
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ];
            })
            ->setLayout([
                [
                    ['key' => 'row1_1', 'column' => FieldBuilder::Col6],
                    ['key' => 'row1_2', 'column' => FieldBuilder::Col6],
                ],
                [
                    ['key' => 'row2_1', 'column' => FieldBuilder::Col12],
                ]
            ])
    )
    ->setFields([
        GateConfig::Field('name')
            ->setTitle('core::tables.user.field.name')
            ->setKeyLayout('row1_1'),
        GateConfig::Field('avatar')
            ->setTitle('core::tables.user.field.avatar')
            ->setType(FieldBuilder::Image)
            ->setFolder('user')
            ->setKeyLayout('row1_1'),
        GateConfig::Field('email')
            ->hideView()
            ->setTitle('core::tables.user.field.email')
            ->setKeyLayout('row1_2'),
        GateConfig::Field('info')
            ->setTitle('core::tables.user.field.info')
            ->setType(FieldBuilder::Textarea)
            ->setKeyLayout('row2_1'),
        GateConfig::Field('password')
            ->hideView()
            ->hideEdit()
            ->setTitle('core::tables.user.field.password')
            ->setType(FieldBuilder::Password)
            ->setKeyLayout('row1_1'),
        GateConfig::FieldStatus()
            ->setKeyLayout('row1_2')
    ]);
