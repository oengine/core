<?php

namespace OEngine\Core\Support\Config;

/**
 * 
 * @method  \OEngine\Core\Support\Config\OptionConfig Disable()
 * @method  \OEngine\Core\Support\Config\OptionConfig Enable()
 * @method  \OEngine\Core\Support\Config\OptionConfig Hide()
 * @method  \OEngine\Core\Support\Config\OptionConfig setClass(string $value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setTitle(string $value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setType($value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setIcon($value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setPermission($value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setSort($value)
 * @method  \OEngine\Core\Support\Config\OptionConfig setAttr($value)
 * 
 * @see  \OEngine\Core\Support\Config\OptionConfig
 */
class OptionConfig  extends BaseConfig
{
    public const OPTION_FIELDS = "OPTION_FIELDS";
    public const FORM = "FORM";
    public function setFields(array $value = []): self
    {
        return $this->setKeyData(self::OPTION_FIELDS, $value);
    }
    public function getFields($value = [])
    {
        return $this->getKeyData(self::OPTION_FIELDS, $value);
    }
    public function setForm($value): self
    {
        return $this->setKeyData(self::FORM, $value);
    }
    public function getForm($value = null): FormConfig|null
    {
        return $this->getKeyData(self::FORM, $value);
    }
    public function getValueInForm($value = null, $default = '')
    {
        $form = $this->getForm();
        if ($form) {
            return $form->getKeyData($value, $default);
        }
        return $default;
    }
}
