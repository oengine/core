<?php

namespace OEngine\Core\Support\Slug;

use OEngine\Core\Facades\Slug;

class SlugBase
{
    private $slug = '';
    private $sort = 0;
    protected $tags = [];
    protected $params = [];
    protected $format_key_value = true;
    public function checkParam($param)
    {
        return isset($this->params[$param]) && $this->params[$param] <> "";
    }
    public function ProcessParams(): self
    {
        $tags = isset($this->tags) ? $this->tags : [];
        $this->params = Slug::getParamByDelimiters($this->slug, $tags,$this->format_key_value);
        return $this;
    }

    public function __construct($slug, $sort = -1)
    {
        $this->slug = $slug;
        $this->sort = $sort;
    }
    public function getSort()
    {
        return $this->sort;
    }
    public function checkSlug()
    {
        return true;
    }
    public function viewSlug()
    {
        return view('404');
    }
}
