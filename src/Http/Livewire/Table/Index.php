<?php

namespace OEngine\Core\Http\Livewire\Table;

use OEngine\Core\Livewire\Modal;
use OEngine\Core\Traits\WithTableIndex;

class Index extends Modal
{
    use WithTableIndex;
    public function mount($module)
    {
        $this->LoadModule($module);
    }
}