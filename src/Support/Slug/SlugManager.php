<?php

namespace OEngine\Core\Support\Slug;


class SlugManager
{
    private const delimiter = '@#$';
    public function __construct()
    {
        //$this->AddSlug(Error404Slug::class);
    }
    public function getParamByDelimiters($slug, $delimiters, $format_key_value = true)
    {
        if (!$delimiters || !is_array($delimiters) || count($delimiters) == 0) return [];
        $delimitersIndex = [];
        foreach ($delimiters as $key) {
            if (str_starts_with($slug, $key)) {
                $delimitersIndex[] = 0 . self::delimiter . $key;
            }
            $index = 1;
            while ($index = strpos($slug, $key, $index)) {
                $delimitersIndex[] = $index . self::delimiter . $key;
                $index++;
            }
        }
        sort($delimitersIndex);
        $newStr = str_replace($delimiters, $delimiters[0], $slug);
        $params = explode($delimiters[0], $newStr);
        $keyValues = [];
        if ($format_key_value) {
            for ($i = 1; $i < count($params); $i++) {
                $key = explode(self::delimiter, $delimitersIndex[$i - 1])[1];
                $value = trim(trim($params[$i], '-'));
                if (isset($keyValues[$key])) {
                    if (is_array($keyValues[$key])) {
                        $keyValues[$key] = [...$keyValues[$key], $value];
                    } else {
                        $keyValues[$key] = [$keyValues[$key], $value];
                    }
                } else {
                    $keyValues[$key] = $value;
                }
            }
        } else {
            for ($i = 0; $i < count($params) - 1; $i++) {
                $key = explode(self::delimiter, $delimitersIndex[$i])[1];
                $value = trim(trim($params[$i], '-'));
                if (isset($keyValues[$key])) {
                    if (is_array($keyValues[$key])) {
                        $keyValues[$key] = [...$keyValues[$key], $value];
                    } else {
                        $keyValues[$key] = [$keyValues[$key], $value];
                    }
                } else {
                    $keyValues[$key] = $value;
                }
            }
        }
        return $keyValues;
    }
    private $arrSlug = [];
    public function AddSlug($class)
    {
        $this->arrSlug[] = $class;
    }
    public function ViewBySlug($slug)
    {
        $arrSlug = $this->arrSlug;
        $index = -1;
        $arrSlugs = array_map(function ($item) use ($slug, $index) {
            $index++;
            return  new $item($slug, $index);
        },  $arrSlug);
        usort($arrSlugs, function ($a, $b) {
            return strcmp($a->getSort(), $b->getSort());
        });
        foreach ($arrSlugs as $item) {
            if ($item->ProcessParams()->checkSlug()) {
                return $item->viewSlug();
            }
        }
        return abort(404);
    }
}
