<?php

namespace OEngine\Core\Support\Slug;

use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

class SlugManager
{
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
                $delimitersIndex[] = ['sort' => 0, 'key' => $key];;
            }
            $index = 1;
            while ($index = strpos($slug, $key, $index)) {
                $delimitersIndex[] = ['sort' => $index, 'key' => $key];
                $index++;
            }
        }
        usort($delimitersIndex, function ($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }
            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });
        $newStr = str_replace($delimiters, $delimiters[0], $slug);
        $params = explode($delimiters[0], $newStr);
        if ($format_key_value) {
            array_shift($params);
        }
        $lenParam = count($delimitersIndex);
        $keyValues = [];
        for ($i = 0; $i <  $lenParam; $i++) {
            $key =  $delimitersIndex[$i]['key'];
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
        return $keyValues;
    }
    public function ToUrl($class, $params)
    {
    }
    private $arrSlug = [];
    public function AddSlug($class)
    {
        $this->arrSlug[] = $class;
    }
    private $arrParameters = [];
    private function setParameter($name, $value)
    {
        $this->arrParameters[$name] = $value;
    }
    public function getParameters()
    {
        return $this->arrParameters ?? [];
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
                // return $item->getParams();
                $route = Route::addRoute('get', '/', $item->viewSlug())->setContainer(app())->bind(request());
                foreach ($item->getParams() as $paramKey => $paramValue) {
                    $paramKey =  str($paramKey)->camel()->toString();
                    $this->setParameter($paramKey, $paramValue);
                    $route->setParameter($paramKey, $paramValue);
                }
                // return $route->parameters();
                return  $route->run();
            }
        }
        return abort(404);
    }
}
