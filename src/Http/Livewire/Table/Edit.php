<?php

namespace OEngine\Core\Http\Livewire\Table;

use OEngine\Core\Livewire\Modal;
use OEngine\Core\Traits\WithTableEdit;

class Edit extends Modal
{
    use WithTableEdit;
    public function mount($module, $dataId = null)
    {
        $this->LoadModule($module, $dataId);
    }
}
