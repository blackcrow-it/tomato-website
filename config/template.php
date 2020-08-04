<?php

use App\Constants\ObjectType;

return [
    'position' => [
        [
            'code' => 'navigator',
            'type' => ObjectType::CATEGORY,
            'name' => 'Thanh điều hướng'
        ],
        [
            'code' => 'course-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Trang chủ - Danh mục khoá học'
        ],
        [
            'code' => 'home-courses',
            'type' => ObjectType::CATEGORY,
            'name' => 'Trang chủ - Giáo trình học online'
        ],

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
            'code' => 'home-courses',
            'type' => ObjectType::COURSE,
            'name' => 'Trang chủ - Giáo trình học online'
        ],
    ]
];
