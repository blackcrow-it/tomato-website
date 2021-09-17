<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Book;
use App\BookRelatedBook;
use App\BookRelatedCourse;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Setting;

class BookController extends Controller
{
    public function all()
    {
        $category = new Category();
        $category->title = 'Tất cả tài liệu';
        $category->link = route('book.all');
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();

        return view('frontend.category.book', [
            'category' => $category,
            'list' => get_books(null, null, true),
            'breadcrumb' => [],
            'consultation_background' => $consultationFormBg->value,
        ]);
    }

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
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_course');

        $relatedBooks = BookRelatedBook::with('related_book')
            ->wherehas('related_book', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('book_id', $book->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_book');

        if (Auth::check()) {
            if (!Auth::user()->is_super_admin && !Auth::user()->roles()->exists()) {
                $book->view++;
            }
        } else {
            $book->view++;
        }
        $book->save();
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();

        return view('frontend.book.detail', [
            'book' => $book,
            'breadcrumb' => Category::ancestorsAndSelf($book->category_id),
            'related_courses' => $relatedCourses,
            'related_books' => $relatedBooks,
            'consultation_background' => $consultationFormBg->value,
        ]);
    }
}
