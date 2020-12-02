<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\BookRelatedCourse;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request, $slug, $id)
    {
        $book = Book::firstWhere([
            'slug' => $slug,
            'enabled' => true
        ]);

        if ($book == null) {
            return redirect()->route('home');
        }

        $relatedCourses = BookRelatedCourse::with('related_course')
            ->wherehas('related_course', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('book_id', $book->id)
            ->get()
            ->pluck('related_course');

        return view('frontend.book.detail', [
            'book' => $book,
            'breadcrumb' => Category::ancestorsAndSelf($book->category_id),
            'related_courses' => $relatedCourses,
        ]);
    }
}
