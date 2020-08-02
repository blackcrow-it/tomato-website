<?php

use App\Constants\ObjectType;

return [
    'position' => [
        [
            'code' => 'slider',
            'type' => ObjectType::POST,
            'name' => 'Trang chủ - Slider'
        ],
        [
            'code' => 'hot-news',
            'type' => ObjectType::POST,
            'name' => 'Trang chủ - Tin tức nổi bật'
        ],
        [
            'code' => 'navigator',
            'type' => ObjectType::CATEGORY,
            'name' => 'Thanh điều hướng'
        ],
        [
            'code' => 'course-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Trang chủ - Danh mục khoá học'
        ]
    ]
];
