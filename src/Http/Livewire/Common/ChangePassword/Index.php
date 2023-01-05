<?php

namespace OEngine\Core\Http\Livewire\Common\ChangePassword;

use OEngine\Core\Livewire\Modal;
use OEngine\Core\Models\User;

class Index extends Modal
{
    public $password = "";
    public $password2 = "";
    public function mount()
    {
        $this->setTitle(__('core::screens.change-password.title'));
    }
    public function DoWork()
    {
        if ($this->password != "" && $this->password == $this->password2) {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $user->password = $this->password;
            $user->save();
            $this->hideModal();
            $this->showMessage(__('core::screens.change-password.message-ok'));
        } else {
            $this->showMessage(__('core::screens.change-password.message-fail'));
        }
    }
    public function render()
    {
        return $this->viewModal('core::common.change-password.index');
    }
}
