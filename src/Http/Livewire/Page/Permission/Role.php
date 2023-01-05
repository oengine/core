<?php

namespace OEngine\Core\Http\Livewire\Page\Permission;

use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Livewire\Modal;
use OEngine\Core\Models\Permission;
use OEngine\Core\Models\Role as ModelsRole;

class Role extends Modal
{
    public $roleId;
    public $role_name;
    public $permission;
    public ModelsRole $role;
    public function mount($roleId)
    {
        $this->roleId = $roleId;
        $this->role = ModelsRole::with('permissions')->find($this->roleId);
        $this->role_name =  $this->role->name;
        $this->permission =  $this->role->permissions->pluck('id', 'id');
        $this->setTitle('Setup:' .  $this->role_name);
    }
    public function doSave()
    {
        $role = ModelsRole::find($this->roleId);
        $role->permissions()->sync(collect($this->permission)->filter(function ($item) {
            return $item > 0;
        })->toArray());
        $this->hideModal();
        $this->ShowMessage("Update successfull!");
    }
    public function getOptionTree()
    {
        return GateConfig::Field('permission')->setFuncData(function () {
            return [
                [
                    'key' => 'core',
                    'text' => 'Root',
                    'skipTop' => true,
                    'value' => '',
                    'show' => true,
                    'isChild' => true
                ],
                ...Permission::all()->map(function ($item) {
                    return [
                        'key' => $item->slug,
                        'text' => $item->name,
                        'value' => $item->id
                    ];
                })->toArray()
            ];
        })->DoFuncData($this->__request,$this);
    }
    public function render()
    {
        return $this->viewModal('core::page.permission.role', [
            'optionTree' => $this->getOptionTree()
        ]);
    }
}
