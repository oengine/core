<?php

namespace OEngine\Core\Widget\Dashboard;


class Index extends \OEngine\Core\Livewire\Widget
{
    public function render()
    {
        return $this->View('views.' . $this->widget_type);
    }
}
