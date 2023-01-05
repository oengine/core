<?php

namespace OEngine\Core\Http\Livewire\Page\Auth;

use Illuminate\Support\Facades\Auth;
use OEngine\Core\Facades\Theme;
use OEngine\Core\Livewire\Modal;

class Login extends Modal
{
    public function boot()
    {
        parent::boot();
        Theme::setLayoutNone();
    }
    public $username;
    public $password;
    public $isRememberMe;

    protected $rules = [
        'password' => 'required|min:1',
        'username' => 'required|min:1',
    ];
    public function DoWork()
    {
         $this->validate();
        if (Auth::attempt(['email' => $this->username, 'password' => $this->password], $this->isRememberMe)) {
            return redirect('/');
        } else {
            $this->showMessage("Login Fail");
        }
    }
    public function mount()
    {
        $this->setTitle('Login to system');
    }
    public function render()
    {
        return $this->viewModal('core::page.auth.login');
    }
}
