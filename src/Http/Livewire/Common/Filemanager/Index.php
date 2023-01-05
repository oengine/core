<?php

namespace OEngine\Core\Http\Livewire\Common\Filemanager;

use OEngine\Core\Facades\Core;
use OEngine\Core\Livewire\Modal;

class Index extends Modal
{
    public $disk;
    public $path_current;
    protected function getListeners()
    {
        return Core::mereArr(parent::getListeners(), [
            'selectPath' => 'eventSelectPath',
        ]);
    }
    public function eventSelectPath($path, $local)
    {
        $this->disk = $local;
        $this->path_current = $path;
    }
    public function mount()
    {
        $this->setTitle("File Manager");
        $this->modal_size = Modal::FullscreenXL;
    }
    public function render()
    {
        return $this->viewModal('core::common.filemanager.index');
    }
}
