<?php

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Livewire\Modal;
use OEngine\Core\Support\Config\ButtonConfig;

return GateConfig::NewItem()
    ->setModel(\OEngine\Core\Models\DataList::class)
    ->setFuncExtendParam(function ($row) {
        return ",'list_key':'" . $row['key'] . "'";
    })
    ->setButtonAppend([
        GateConfig::Button('core::tables.data_list.button.list')
            ->setIcon('<i class="bi bi-magic"></i>')
            ->setClass('btn btn-primary btn-sm')
            // ->setPermission('core.role.permission')
            ->setDoComponent('core::table.index', function ($id, $row) {
                return "{'list_key':'" . $row['key'] . "','list_id':" . $id . ",'module':'data_item'}";
            })
            ->setType(ButtonConfig::TYPE_UPDATE)
    ])
    ->setFuncDataChangeEvent(function ($param, $commponent, $request) {
        if (isset($param['list_key'])) {
            remove_cache_list($param['list_key']);
        } else {
            remove_cache_list($commponent->key);
        }
    })
    ->setForm(GateConfig::Form()->setSize(Modal::Large))
    ->setFields([
        GateConfig::Field('key')
            ->setTitle('core::tables.data_list.field.key')
            ->hideEdit()
            ->disableEdit(),
        GateConfig::Field('title')
            ->setTitle('core::tables.data_list.field.title'),
        GateConfig::Field('content')
            ->setDataDefault('')
            ->setType(FieldBuilder::Quill)
            ->setTitle('core::tables.data_list.field.content'),
        GateConfig::FieldStatus('status', 'data_list')
            ->setKeyLayout('row1_1'),
        // GateConfig::Field('item_default')
        //     ->setTitle('core::tables.data_list.field.item_default')
    ]);
