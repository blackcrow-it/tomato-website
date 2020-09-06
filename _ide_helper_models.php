<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Cart
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $type
 * @property int $object_id
 * @property int $amount
 * @property int|null $price
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App{
/**
 * App\Category
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $icon
 * @property string|null $cover
 * @property string|null $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property string $type
 * @property string|null $url
 * @property bool $enabled
 * @property-read \Kalnoy\Nestedset\Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CategoryPosition[] $position
 * @property-read int|null $position_count
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Category d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUrl($value)
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\CategoryPosition
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $category_id
 * @property int $order_in_position
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereOrderInPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryPosition whereUpdatedAt($value)
 */
	class CategoryPosition extends \Eloquent {}
}

namespace App{
/**
 * App\Course
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $thumbnail
 * @property string|null $cover
 * @property string|null $description
 * @property string|null $content
 * @property int $view
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $category_id
 * @property int $order_in_category
 * @property int|null $price
 * @property int|null $original_price
 * @property string|null $intro_youtube_id
 * @property-read \App\User|null $author
 * @property-read \App\Category|null $category
 * @property-read \App\User|null $editor
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CoursePosition[] $position
 * @property-read int|null $position_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIntroYoutubeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOrderInCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereView($value)
 */
	class Course extends \Eloquent {}
}

namespace App{
/**
 * App\CoursePosition
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $course_id
 * @property int $order_in_position
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereOrderInPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePosition whereUpdatedAt($value)
 */
	class CoursePosition extends \Eloquent {}
}

namespace App{
/**
 * App\CourseRelatedCourse
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $course_id
 * @property int $related_course_id
 * @property-read \App\Course $course
 * @property-read \App\Course $related_course
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse whereRelatedCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRelatedCourse whereUpdatedAt($value)
 */
	class CourseRelatedCourse extends \Eloquent {}
}

namespace App{
/**
 * App\Invoice
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUserId($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App{
/**
 * App\InvoiceItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $invoice_id
 * @property string $type
 * @property int $object_id
 * @property int $amount
 * @property int|null $price
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceItem whereUpdatedAt($value)
 */
	class InvoiceItem extends \Eloquent {}
}

namespace App{
/**
 * App\Lesson
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $course_id
 * @property string $title
 * @property bool $enabled
 * @property int $order_in_course
 * @property-read \App\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Part[] $parts
 * @property-read int|null $parts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereOrderInCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 */
	class Lesson extends \Eloquent {}
}

namespace App{
/**
 * App\Part
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $lesson_id
 * @property string $title
 * @property string $type
 * @property bool $enabled
 * @property int $order_in_lesson
 * @property-read mixed $url
 * @property-read \App\Lesson $lesson
 * @property-read \App\PartContent|null $part_content
 * @property-read \App\PartVideo|null $part_video
 * @property-read \App\PartYoutube|null $part_youtube
 * @method static \Illuminate\Database\Eloquent\Builder|Part newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Part newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Part query()
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereOrderInLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereUpdatedAt($value)
 */
	class Part extends \Eloquent {}
}

namespace App{
/**
 * App\PartContent
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $part_id
 * @property string|null $content
 * @property-read \App\Part $part
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartContent whereUpdatedAt($value)
 */
	class PartContent extends \Eloquent {}
}

namespace App{
/**
 * App\PartVideo
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $part_id
 * @property string|null $s3_path
 * @property-read \App\Part $part
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo whereS3Path($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartVideo whereUpdatedAt($value)
 */
	class PartVideo extends \Eloquent {}
}

namespace App{
/**
 * App\PartYoutube
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $part_id
 * @property string|null $youtube_id
 * @property-read \App\Part $part
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube query()
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartYoutube whereYoutubeId($value)
 */
	class PartYoutube extends \Eloquent {}
}

namespace App{
/**
 * App\Post
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $thumbnail
 * @property string|null $cover
 * @property string|null $description
 * @property string|null $content
 * @property int $view
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $category_id
 * @property int $order_in_category
 * @property-read \App\User|null $author
 * @property-read \App\Category|null $category
 * @property-read \App\User|null $editor
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PostPosition[] $position
 * @property-read int|null $position_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOrderInCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereView($value)
 */
	class Post extends \Eloquent {}
}

namespace App{
/**
 * App\PostPosition
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $post_id
 * @property int $order_in_position
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition whereOrderInPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostPosition whereUpdatedAt($value)
 */
	class PostPosition extends \Eloquent {}
}

namespace App{
/**
 * App\PostRelatedCourse
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $post_id
 * @property int $related_course_id
 * @property-read \App\Post $post
 * @property-read \App\Course $related_course
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse whereRelatedCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedCourse whereUpdatedAt($value)
 */
	class PostRelatedCourse extends \Eloquent {}
}

namespace App{
/**
 * App\PostRelatedPost
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $post_id
 * @property int $related_post_id
 * @property-read \App\Post $post
 * @property-read \App\Post $related_post
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost whereRelatedPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostRelatedPost whereUpdatedAt($value)
 */
	class PostRelatedPost extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string|null $name
 * @property string $username
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $money
 * @property string|null $google_id
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $address
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\UserCourse
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $course_id
 * @property string|null $expires_on
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereExpiresOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCourse whereUserId($value)
 */
	class UserCourse extends \Eloquent {}
}

