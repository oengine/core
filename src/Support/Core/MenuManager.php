<?php

namespace OEngine\Core\Support\Core;

use OEngine\Core\Builder\Menu\MenuBuilder;

class MenuManager
{
    private $menuPostion = [];
    public function addMenuItem($text, $icon = '', $permission = '', $actionValue = '', $actionType = MenuBuilder::ItemLink, $class = '', $id = '', $sort = 500, $postion = 'sidebar'): MenuBuilder
    {
        if (!isset($this->menuPostion[$postion])) $this->menuPostion[$postion] = new MenuBuilder(false, $postion);
        $this->menuPostion[$postion]->addItem($text, $icon, $permission, $actionValue, $actionType, $class, $id, $sort);
        return $this->menuPostion[$postion];
    }
    public function addMenuSub($callback, $text, $icon = '', $permission = '', $actionValue = '', $actionType = MenuBuilder::ItemLink, $class = '', $id = '', $sort = 500, $postion = 'sidebar'): MenuBuilder
    {
        if (!isset($this->menuPostion[$postion])) $this->menuPostion[$postion] = new MenuBuilder(false, $postion);
        $this->menuPostion[$postion]->addItemWith($callback, $text, $icon, $permission, $actionValue, $actionType, $class, $id, $sort);
        return $this->menuPostion[$postion];
    }
    public function doRender($postion)
    {
        if (!isset($this->menuPostion[$postion])) return;
        $this->menuPostion[$postion]->BindData()->RenderHtml();
    }
    public function getHtml($postion)
    {
        if (!isset($this->menuPostion[$postion])) return '';
        return  $this->menuPostion[$postion]->BindData()->ToHtml();
    }
}
