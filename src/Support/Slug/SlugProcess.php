<?php

namespace OEngine\Core\Support\Slug;

use OEngine\Core\Facades\Slug;

class SlugProcess
{
    public function __invoke($slug)
    {
        return Slug::ViewBySlug($slug);
    }
}
