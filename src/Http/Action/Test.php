<?php

namespace OEngine\Core\Http\Action;

use OEngine\Core\Support\Core\ActionBase;

class Test extends ActionBase
{
    public function DoAction()
    {
        $this->component->showMessage("Xin chào, i'am vietnam");
    }
}
