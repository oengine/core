<?php

namespace OEngine\Core\Traits;

use OEngine\Core\Models\CustomFieldValue;

trait WithCustomField
{
    public function bootWithCustomField()
    {
        static::deleting(function ($model) {
            $model->getConnectionResolver()->transaction(function () use ($model) {
                $model->CustomFields()->delete();
            });
        });
    }
    public function CustomFields()
    {
        return $this->morphMany(CustomFieldValue::class, 'custom_field');
    }
}
