<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Category;
use App\CategoryPosition;
use App\Repositories\CategoryRepo;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

class CategoryController extends Controller
{
    private $categoryRepo;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function list(Request $request)
    {
        $currentCategory = Category::with('ancestors')->find($request->input('filter.parent_id'));

        if ($currentCategory == null && $request->input('filter.parent_id') != null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $list = $this->categoryRepo->getByFilterQuery($request->input('filter'))->paginate();

        $categories = Category::all()->toTree();

        return view('backend.category.list', [
            'list' => $list,
            'currentCategory' => $currentCategory,
            'categories' => categories_traverse($categories)
        ]);
    }

    public function add()
    {
        $categories = Category::get()->toTree();

        return view('backend.category.edit', [
            'categories' => categories_traverse($categories)
        ]);
    }

    public function submitAdd(CategoryRequest $request)
    {
        $category = new Category();

        try {
            DB::beginTransaction();
            $this->processCategoryFromRequest($request, $category);
            DB::commit();

            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Thêm danh mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.category.list')
                ->withErrors('Thêm danh mục thất bại.');
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $category->__template_position = $category->position->pluck('code')->toArray();

        $categories = Category::where('id', '!=', $category->id)->get()->toTree();

        return view('backend.category.edit', [
            'data' => $category,
            'categories' => categories_traverse($categories)
        ]);
    }

    public function submitEdit(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $this->processCategoryFromRequest($request, $category);
            DB::commit();

            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Thay đổi danh mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.category.list')
                ->withErrors('Thay đổi danh mục thất bại.');
        }
    }

    private function processCategoryFromRequest(Request $request, Category $category)
    {
        $data = $request->input();
        $category->fill($data);
        $category->save();

        $positionData = CategoryPosition::where('category_id', $category->id)->get();
        CategoryPosition::where('category_id', $category->id)->delete();
        $templatePositionCodeArray = $request->input('__template_position', []);
        foreach ($templatePositionCodeArray as $code) {
            $position = new CategoryPosition();
            $position->code = $code;
            $position->category_id = $category->id;
            $position->order_in_position = $positionData->firstWhere('code', $code)->order_in_position ?? 0;
            $position->save();
        }
    }

    public function submitDelete($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();

            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Xóa danh mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.category.list')
                ->withErrors('Xóa danh mục thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $post = Category::findOrFail($request->input('id'));
        $post->enabled = $request->input('enabled');
        $post->timestamps = false;
        $post->save();
    }

    public function submitOrderInPosition(Request $request)
    {
        $position = CategoryPosition::where([
            'category_id'   => $request->input('id'),
            'code'          => $request->input('code')
        ])->firstOrFail();
        $position->order_in_position = intval($request->input('order_in_position'));
        $position->timestamps = false;
        $position->save();
    }
}
