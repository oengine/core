<?php

namespace OEngine\Core\Http\Livewire\Common\Layout;

use OEngine\Core\Livewire\Component;
use OEngine\Core\Livewire\Contracts\SkipLoad;

class Index extends Component implements SkipLoad
{
    public function render()
    {
        return view('core::common.layout.index');
    }
    protected function ensureViewHasValidLivewireLayout($view)
    {
        $layout = $view->livewireLayout ?? [];

        $isValid = isset($layout['view'], $layout['type'], $layout['params'], $layout['slotOrSection']);

        if (!$isValid) {
            $view->layout($layout['view'] ?? config('livewire.layout'), $layout['params'] ?? []);
            $view->slot($layout['slotOrSection'] ?? $view->livewireLayout['slotOrSection']);
        }
    }
}
