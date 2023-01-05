<?php

namespace OEngine\Core\Support\Config;

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Facades\Core;

/**
 * 
 * @method  \OEngine\Core\Support\Config\ConfigManager Disable()
 * @method  \OEngine\Core\Support\Config\ConfigManager Enable()
 * @method  \OEngine\Core\Support\Config\ConfigManager Hide()
 * @method  \OEngine\Core\Support\Config\ConfigManager setClass(string $value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setTitle(string $value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setType($value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setIcon($value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setPermission($value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setSort($value)
 * @method  \OEngine\Core\Support\Config\ConfigManager setAttr($value)
 * 
 * @see  \OEngine\Core\Support\Config\ConfigManager
 */

class ConfigManager  extends BaseConfig
{
    public const FUNC_QUERY = "FUNC_QUERY";
    public const FUNC_DATA = "FUNC_DATA";
    public const FUNC_FILTER = "FUNC_FILTER";
    public const FUNC_ROW = "FUNC_ROW";
    public const FUNC_DATA_CHANGE_EVENT = "FUNC_DATA_CHANGE_EVENT";
    public const FUNC_EXTEND_PARAM = "FUNC_EXTEND_PARAM";
    public const MODEL_KEY = "MODEL_KEY";
    public const MODEL = "MODEL";
    public const FORM = "FORM";
    public const CLASS_TABLE = "CLASS_TABLE";
    public const FIELDS = "FIELDS";
    public const ACTION_TITLE = "ACTION_TITLE";
    public const BUTTON_APPEND = "ACTION_BUTTON_APPEND";
    public const PAGE_SIZE = "PAGE_SIZE";
    public const ACTION_ADD = "ACTION_ADD";
    public const ACTION_EDIT = "ACTION_EDIT";
    public const ACTION_REMOVE = "ACTION_REMOVE";
    public const ACTION_FILTER = "ACTION_FILTER";
    public const ACTION_SORT = "ACTION_SORT";
    public const INPORT_EXCEL = "INPORT_EXCEL";
    public const EXPORT_EXCEL = "EXPORT_EXCEL";
    public const POLL = "POLL";

    public const INCLUDE_BEFORE = "INCLUDE_BEFORE";

    public const INCLUDE_AFTER = "INCLUDE_AFTER";

    public function disableRemove(): self
    {
        return $this->setKeyData(self::ACTION_REMOVE, false);
    }
    public function disableEdit(): self
    {
        return $this->setKeyData(self::ACTION_EDIT, false);
    }
    public function disableAdd(): self
    {
        return $this->setKeyData(self::ACTION_ADD, false);
    }
    public function disableFilter(): self
    {
        return $this->setKeyData(self::ACTION_FILTER, false);
    }
    public function disableSort(): self
    {
        return $this->setSort(false);
    }
    public function getRemove()
    {
        return $this->getKeyData(self::ACTION_REMOVE, true);
    }
    public function getEdit()
    {
        return $this->getKeyData(self::ACTION_EDIT, true);
    }
    public function getAdd()
    {
        return $this->getKeyData(self::ACTION_ADD, true);
    }
    public function checkFilter()
    {
        return $this->getKeyData(self::ACTION_FILTER, true) == true;
    }
    public function checkSort()
    {
        return $this->getSort(true) == true;
    }
    public function setFuncExtendParam(callable $value): self
    {
        return $this->setKeyData(self::FUNC_EXTEND_PARAM, $value);
    }
    public function setFuncDataChangeEvent(callable $value): self
    {
        return $this->setKeyData(self::FUNC_DATA_CHANGE_EVENT, $value);
    }
    public function setFuncFilter(callable $value): self
    {
        return $this->setKeyData(self::FUNC_FILTER, $value);
    }
    public function setFuncQuery(callable $value): self
    {
        return $this->setKeyData(self::FUNC_QUERY, $value);
    }
    public function setFuncData($value): self
    {
        return $this->setKeyData(self::FUNC_DATA, $value);
    }
    public function setPageSize($value): self
    {
        return $this->setKeyData(self::PAGE_SIZE, $value);
    }
    public function setFields(array $value = []): self
    {
        return $this->setKeyData(self::FIELDS, $value);
    }
    public function setForm($value): self
    {
        return $this->setKeyData(self::FORM, $value);
    }
    public function setModel($value): self
    {
        return $this->setKeyData(self::MODEL, $value);
    }
    public function setModelKey($value = "id"): self
    {
        return $this->setKeyData(self::MODEL_KEY, $value);
    }
    public function setIncludeAfter($value): self
    {
        return $this->setKeyData(self::INCLUDE_AFTER, $value);
    }
    public function setIncludeBefore($value): self
    {
        return $this->setKeyData(self::INCLUDE_BEFORE, $value);
    }
    public function setPoll($value): self
    {
        return $this->setKeyData(self::POLL, $value);
    }
    public function setButtonAppend(array $value = [])
    {
        $this[self::BUTTON_APPEND] = $value;
        return $this;
    }

    public function getFuncExtendParam($value = null)
    {
        return $this->getKeyData(self::FUNC_EXTEND_PARAM, $value);
    }
    public function getFuncDataChangeEvent($value = null)
    {
        return $this->getKeyData(self::FUNC_DATA_CHANGE_EVENT, $value);
    }
    public function getFuncFilter($value = null)
    {
        return $this->getKeyData(self::FUNC_FILTER, $value);
    }
    public function getFuncQuery($value = null)
    {
        return $this->getKeyData(self::FUNC_QUERY, $value);
    }
    public function getFuncData($value = null)
    {
        return $this->getKeyData(self::FUNC_DATA, $value);
    }
    public function getPageSize($value = null)
    {
        return $this->getKeyData(self::PAGE_SIZE, $value);
    }
    public function getFields(array $value = [])
    {
        return $this->getKeyData(self::FIELDS, $value);
    }
    public function getForm($value = null): FormConfig|null
    {
        return $this->getKeyData(self::FORM, $value);
    }
    public function getModel($value = null)
    {
        return $this->getKeyData(self::MODEL, $value);
    }
    public function getModelKey($value = "id")
    {
        return $this->getKeyData(self::MODEL_KEY, $value);
    }
    public function getIncludeAfter($value = null)
    {
        return $this->getKeyData(self::INCLUDE_AFTER, $value);
    }
    public function getIncludeBefore($value = null)
    {
        return $this->getKeyData(self::INCLUDE_BEFORE, $value);
    }
    public function getPoll($value = null)
    {
        return $this->getKeyData(self::POLL, $value);
    }
    public function getButtonAppend(array $value = [])
    {
        return $this->getKeyData(self::BUTTON_APPEND, $value);
    }
    public function Field($field = ''): FieldConfig
    {
        return (new FieldConfig())->setField($field);
    }
    public function FieldStatus($field = 'status', $model = 'user', $modelKey = 'id'): FieldConfig
    {
        return $this->Field($field)
            ->setDataDefault(1)
            ->setFuncData(function () {
                return collect([0, 1])->map(function ($item) {
                    return [
                        'id' => $item,
                        'name' => __('core::enums.status.' . $item)
                    ];
                });
            })
            ->setFuncCell(function ($value, $row, $column) use ($model, $field, $modelKey) {
                if (Core::checkPermission('core.' . $model . '.change-status')) {
                    if ($value == 1) {
                        return  $this->Button('core::enums.status.1')
                            ->setClass('btn btn-primary btn-sm text-nowrap')
                            ->setDoChangeField("{'id':'" . $row[$modelKey] . "','field':'" . $field . "','value':0,'key':'" . $modelKey . "'}")
                            ->toHtml();
                    }
                    return $this->Button('core::enums.status.0')
                        ->setClass('btn btn-warning btn-sm text-nowrap')
                        ->setDoChangeField("{'id':'" . $row[$modelKey] . "','field':'" . $field . "','value':1,'key':'" . $modelKey . "'}")
                        ->toHtml();
                }
                if ($value == 1) {
                    return __('core::enums.status.1');
                }
                return __('core::enums.status.0');
            })
            ->setTitle('core::tables.user.field.status')
            ->setType(FieldBuilder::Dropdown);
    }
    public function Form(): FormConfig
    {
        return new FormConfig();
    }
    public function Button($title = ''): ButtonConfig
    {
        return (new ButtonConfig())->setTitle($title);
    }
    public function Option($title = ''): OptionConfig
    {
        return (new OptionConfig())->setTitle($title);
    }
    public function Widget($title = ''): WidgetConfig
    {
        return (new WidgetConfig())->setTitle($title);
    }
    public function NewItem($title = ''): self
    {
        return (new self)->setTitle($title);
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
