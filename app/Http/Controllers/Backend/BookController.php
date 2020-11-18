<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Book;
use App\BookPosition;
use App\BookRelatedCourse;
use App\Repositories\BookRepo;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Route;

class BookController extends Controller
{
    private $bookRepo;

    public function __construct(BookRepo $bookRepo)
    {
        $this->bookRepo = $bookRepo;
    }

    public function list(Request $request)
    {
        $list = $this->bookRepo->getByFilterQuery($request->input('filter'))->paginate();

        return view('backend.book.list', [
            'list' => $list,
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function add()
    {
        return view('backend.book.edit', [
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitAdd(BookRequest $request)
    {
        $book = new Book;

        try {
            DB::beginTransaction();
            $this->processBookFromRequest($request, $book);
            DB::commit();

            return redirect()
                ->route('admin.book.list')
                ->with('success', 'Thêm sách mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.book.list')
                ->withErrors('Thêm sách mới thất bại.');
        }
    }

    public function edit($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return redirect()->route('admin.book.list')->withErrors('Sách không tồn tại hoặc đã bị xóa.');
        }

        $book->__template_position = $book->position->pluck('code')->toArray();

        return view('backend.book.edit', [
            'data' => $book,
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitEdit(BookRequest $request, $id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return redirect()->route('admin.book.list')->withErrors('Sách không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $this->processBookFromRequest($request, $book);
            DB::commit();

            return redirect()
                ->route('admin.book.list')
                ->with('success', 'Thay đổi sách thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.book.list')
                ->withErrors('Thay đổi sách thất bại.');
        }
    }

    private function processBookFromRequest(Request $request, Book $book)
    {
        $data = $request->all();
        $book->fill($data);
        $book->save();

        $positionData = BookPosition::where('book_id', $book->id)->get();
        BookPosition::where('book_id', $book->id)->delete();
        $templatePositionCodeArray = $request->input('__template_position', []);
        foreach ($templatePositionCodeArray as $code) {
            $position = new BookPosition();
            $position->code = $code;
            $position->book_id = $book->id;
            $position->order_in_position = $positionData->firstWhere('code', $code)->order_in_position ?? 0;
            $position->save();
        }

        BookRelatedCourse::where('book_id', $book->id)->delete();
        $relatedCourseIds = $request->input('__related_courses', []);
        foreach ($relatedCourseIds as $relatedCourseId) {
            $related = new BookRelatedCourse();
            $related->book_id = $book->id;
            $related->related_course_id = $relatedCourseId;
            $related->save();
        }
    }

    public function submitDelete($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return redirect()->route('admin.book.list')->withErrors('Sách không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $book->delete();
            DB::commit();

            return redirect()
                ->route('admin.book.list')
                ->with('success', 'Xóa sách thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.book.list')
                ->withErrors('Xóa sách thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $book = Book::findOrFail($request->input('id'));
        $book->enabled = $request->input('enabled');
        $book->timestamps = false;
        $book->save();
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::where('type', ObjectType::BOOK)
                ->orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function submitOrderInCategory(Request $request)
    {
        $book = Book::findOrFail($request->input('id'));
        $book->order_in_category = intval($request->input('order_in_category'));
        $book->timestamps = false;
        $book->save();
    }

    public function submitOrderInPosition(Request $request)
    {
        $position = BookPosition::where([
            'book_id' => $request->input('id'),
            'code' => $request->input('code')
        ])->firstOrFail();
        $position->order_in_position = intval($request->input('order_in_position'));
        $position->timestamps = false;
        $position->save();
    }

    public function getSearchBook(Request $request)
    {
        $keyword = $request->input('keyword');
        if (empty($keyword)) return [];

        $query = Book::where('enabled', true)
            ->orderBy('updated_at', 'desc');

        if (strpos($keyword, config('app.url')) === 0) {
            $route = Route::getRoutes()->match(Request::create($keyword));
            $query->where('slug', $route->slug);
        } else {
            $query->where('title', 'ilike', "%$keyword%");
        }

        return $query->get();
    }

    public function getRelatedCourse(Request $request)
    {
        $id = $request->input('id');
        return BookRelatedCourse::with('related_course')
            ->where('book_id', $id)
            ->get()
            ->pluck('related_course');
    }
}
