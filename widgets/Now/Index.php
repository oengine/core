<?php

namespace OEngine\Core\Widget\Now;

class Index extends \OEngine\Core\Livewire\Widget
{
    public $isServer = false;
    public function mount($isServer = false)
    {
        $this->isServer = $isServer;
    }
    public function render()
    {
        if ($this->isServer) {
            return $this->View('views.server');
        }
        return $this->View('views.index');
    }
}
