<?php

namespace OEngine\Core\Builder;

interface IBuilder
{
    public function Config();

    public function BindData();

    public function RenderHtml();

    public function ToHtml();
}
