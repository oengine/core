<?php

namespace OEngine\Core\Support\Module;

use OEngine\Core\Traits\WithSystemExtend;

class ModuleManager
{
    use WithSystemExtend;
    public function getName()
    {
        return "module";
    }
}
