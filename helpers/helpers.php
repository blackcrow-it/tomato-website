<?php
if (!function_exists('categories_traverse')) {
    function categories_traverse($nodes, $prefix = '|--- ')
    {
        $traverse = function ($categories, $prefix) use (&$traverse) {
            $tree = collect();

            foreach ($categories as $category) {
                $category->title = ($prefix ? "$prefix" : '') . $category->title;

                $children = $traverse($category->children, $prefix . '|--- ');

                $tree->push($category);
                $tree = $tree->merge($children);
            }

            return $tree;
        };

        return $traverse($nodes, $prefix);
    }
}

if (!function_exists('currency')) {
    function currency($money, $default = 'Miễn phí')
    {
        $money = intval($money);

        if ($money == 0) return $default;

        return number_format($money, 0, '.', ' ');
    }
}
