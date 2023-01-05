<?php

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Facades\GateConfig;
use OEngine\Core\Models\User;

return GateConfig::Widget('core::dashboard.user.title')
   // ->setPoll('.750ms')
    ->setColumn(FieldBuilder::Col3)
    ->setFuncData(function () {
        return User::count();
    })
    ->setClass('border-primary bg-primary text-white')
    ->setSort(0)
    ->setIcon('bi bi-people');
