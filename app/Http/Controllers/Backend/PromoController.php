<?php

namespace App\Http\Controllers\Backend;

use App\Constants\PromoType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AddEditPromoRequest;
use App\Http\Requests\Backend\PromoRequest;
use App\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function list()
    {
        $list = Promo::orderBy('created_at', 'desc')->paginate();

        return view('backend.promo.list', [
            'list' => $list
        ]);
    }

    public function getItem($id)
    {
        $promo = Promo::find($id);
        if (!$promo) return null;

        $comboCourses = Course::where('enabled', true)
            ->whereIn('id', $promo->combo_courses ?? [])
            ->orderBy('title', 'asc')
            ->get();

        return [
            'promo' => $promo,
            'combo_courses' => $comboCourses
        ];
    }

    public function add()
    {
        return view('backend.promo.edit');
    }

    public function submitAdd(PromoRequest $request)
    {
        $this->processPromoFromRequest($request, new Promo());

        return redirect()
            ->route('admin.promo.list')
            ->with('success', 'Thêm mã khuyến mãi mới thành công.');
    }

    public function edit($id)
    {
        $promo = Promo::find($id);
        if ($promo == null) {
            return redirect()->route('admin.promo.list')->withErrors('Mã khuyến mãi không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.promo.edit', [
            'promo' => $promo
        ]);
    }

    public function submitEdit(PromoRequest $request, $id)
    {
        $promo = Promo::find($id);
        if ($promo == null) {
            return redirect()->route('admin.promo.list')->withErrors('Mã khuyến mãi không tồn tại hoặc đã bị xóa.');
        }

        $this->processPromoFromRequest($request, $promo);

        return redirect()
            ->route('admin.promo.list')
            ->with('success', 'Mã khuyến mãi thay đổi thành công.');
    }

    private function processPromoFromRequest(Request $request, Promo $promo)
    {
        $promo->fill($request->input('promo'));
        $promo->save();
    }

    public function submitDelete($id)
    {
        $promo = Promo::find($id);
        if ($promo == null) {
            return redirect()->route('admin.promo.list')->withErrors('Mã khuyến mãi không tồn tại hoặc đã bị xóa.');
        }

        if ($promo->invoices()->count() > 0) {
            return redirect()->route('admin.promo.list')->withErrors('Mã khuyến mãi đang liên kết với giỏ hàng hoặc đơn hàng. Không thể xóa.');
        }

        $promo->delete();

        return redirect()
            ->route('admin.promo.list')
            ->with('success', 'Xóa mã khuyến mãi thành công.');
    }
}
