<?php

namespace OEngine\Core\Http\Livewire\Common\Profile;

use Illuminate\Support\Facades\Auth;
use OEngine\Core\Facades\Core;
use OEngine\Core\Livewire\Modal;

class Index extends Modal
{
    public $fullname;
    public function mount()
    {
        $this->fullname = auth()->user()->name;
    }
    public function DoLogout(){
        Auth::logout();
        return $this->redirectCurrent();
    }
    public function render()
    {
        return $this->viewModal('core::common.profile.index');
    }
}
