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
            'code' => 'combo-course-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Danh mục combo khoá học'
        ],
        [
            'code' => 'post-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Danh mục tin tức'
        ],
        [
            'code' => 'book-categories',
            'type' => ObjectType::CATEGORY,
            'name' => 'Danh mục sách'
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
            'name' => 'Trang chủ - Tin nổi bật'
        ],
        // END POST POSITION

        // START COURSE POSITION
        [
            'code' => 'home-courses',
            'type' => ObjectType::COURSE,
            'name' => 'Trang chủ - Giáo trình học online'
        ],
        // END COURSE POSITION

        // START BOOK POSITION
        [
            'code' => 'home-books',
            'type' => ObjectType::BOOK,
            'name' => 'Trang chủ - Thư viện sách'
        ],
        // END BOOK POSITION
    ],
    'paginate' => [
        'list' => [
            ObjectType::COURSE  => 12,
            ObjectType::POST    => 12,
            ObjectType::BOOK    => 12,
        ],
    ],
];
