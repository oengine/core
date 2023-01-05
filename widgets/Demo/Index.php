<?php

namespace OEngine\Core\Widget\Demo;


class Index extends \OEngine\Core\Livewire\Widget
{
    public function render()
    {
        return $this->View('views.index');
    }
}
