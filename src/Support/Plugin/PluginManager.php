<?php

namespace OEngine\Core\Support\Plugin;

use OEngine\Core\Traits\WithSystemExtend;

class PluginManager
{
    use WithSystemExtend;
    public function getName()
    {
        return "plugin";
    }
}
