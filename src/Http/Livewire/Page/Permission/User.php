<?php

namespace OEngine\Core\Http\Livewire\Page\Permission;

use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Models\User as ModelsUser;
use OEngine\Core\Livewire\Modal;
use OEngine\Core\Models\Permission;
use OEngine\Core\Models\Role;

class User extends Modal
{
    public $userId;
    public $user_name;
    public $role;
    public $permission;
    public function mount($userId)
    {
        $this->userId = $userId;
        $user = ModelsUser::with('roles', 'permissions')->find($this->userId);
        $this->user_name = $user->email;
        $this->role = $user->roles->pluck('id', 'id');
        $this->permission = $user->permissions->pluck('id', 'id');
        $this->setTitle('Setup:' . $this->user_name);
    }
    public function doSave()
    {
        $user = ModelsUser::find($this->userId);
        $user->permissions()->sync(collect($this->permission)->filter(function ($item) {
            return $item > 0;
        })->toArray());
        $user->roles()->sync(collect($this->role)->filter(function ($item) {
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
        return $this->viewModal('core::page.permission.user', [
            'roleAll' => Role::orderby('name')->get(),
            'optionTree' => $this->getOptionTree()
        ]);
    }
}
