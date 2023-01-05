<?php

namespace OEngine\Core\Support\Theme;

use OEngine\Core\Facades\Core;
use OEngine\Core\Support\Core\Assets;
use OEngine\Core\Support\Core\DataInfo;
use OEngine\Core\Traits\WithSystemExtend;
use Illuminate\Support\Facades\Artisan;

class ThemeManager
{
    use WithSystemExtend;
    public function getName()
    {
        return "theme";
    }
    private $layout;
    private Assets $assets;
    private ?DataInfo $data_active;
    public function setTitle($title)
    {
        $this->getAssets()->setData('page_title', $title);
    }
    public function AddScript($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
    {
        $this->getAssets()->AddScript($local, $contentOrPath, $cdn, $priority, $isLink);
    }
    public function AddStyle($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
    {
        $this->getAssets()->AddStyle($local, $contentOrPath, $cdn, $priority, $isLink);
    }

    public function getAssets(): Assets
    {
        return $this->assets ?? ($this->assets = new Assets());
    }
    public function setLayoutNone()
    {
        $this->setLayout('none');
    }
    public function setLayout($layout)
    {
        $this->layout = 'theme::' . $layout;
    }
    public function findAndActive($theme)
    {
        $theme_data = $this->find($theme);
        if ($theme_data == null) return null;
        if ($parent = $theme_data->getValue('parent')) {
            $this->findAndActive($parent);
        }
        $theme_data->DoRegister('theme');

        return $theme_data;
    }

    public function getStatusData($theme)
    {
        if (isset($theme['admin']) && $theme['admin'] == 1) {
            return get_option('page_admin_theme') == $theme->getKey() ? 1 : 0;
        } else {
            return get_option('page_site_theme') == $theme->getKey() ? 1 : 0;
        }
    }
    public function setStatusData($theme, $value)
    {
        if (isset($theme['admin']) && $theme['admin'] == 1) {
            set_option('page_admin_theme', $theme->getKey());
        } else {
            set_option('page_site_theme', $theme->getKey());
        }
        Core::reModuleLink();
    }
    public function Layout($layout = '')
    {
        if (!isset($this->data_active) || !$this->data_active) {

            if (Request()->route()->getPrefix() === Core::adminPrefix()) {
                $this->data_active = $this->findAndActive(apply_filters("filter_theme_layout", get_option('page_admin_theme', 'oengine-admin')));
            } else {
                $this->data_active = $this->findAndActive(apply_filters("filter_theme_layout", get_option('page_site_theme', 'oengine-none')));
            }
            if ($this->data_active == null) {
                $this->data_active = $this->findAndActive('oengine-none');
            }
            if ($this->data_active) {
                if ($layout != '') {
                    return $layout;
                }
                if (!$this->layout) {
                    $this->layout = 'theme::' .   $this->data_active->getValue('layout', 'layout');
                }
            }
        }
        if ($layout != '') {
            return $layout;
        }
        return $this->layout;
    }
}
