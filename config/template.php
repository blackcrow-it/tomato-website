<?php

use App\Constants\ObjectType;

return [
    'position' => [
        // START CATEGORY POSITION
        [
            'code' => 'navigator',
            'type' => ObjectType::CATEGORY,
            'name' => 'Thanh điều hướng'
        ],
        [
            'code' => 'course-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Danh mục khoá học'
        ],
        [
            'code' => 'home-courses',
            'type' => ObjectType::CATEGORY,
            'name' => 'Trang chủ - Giáo trình học online'
        ],
        // END CATEGORY POSITION

        // START POST POSITION
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
        // END POST POSITION

        // START COURSE POSITION
        [
            'code' => 'home-courses',
            'type' => ObjectType::COURSE,
            'name' => 'Trang chủ - Giáo trình học online'
        ],
        // END COURSE POSITION
    ],
    'paginate' => [
        'list' => [
            ObjectType::COURSE  => 12,
            ObjectType::POST    => 12,
        ],
    ],
];
