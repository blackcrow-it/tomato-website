<?php

namespace App\Http\Controllers\Backend;

use App\Constants\InvoiceStatus;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Repositories\UserRepo;
use App\UserCourse;
use DB;
use Illuminate\Http\Request;
use Log;

class InvoiceController extends Controller
{
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $list = Invoice::with('items')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('backend.invoice.list', [
            'list' => $list
        ]);
    }

    public function detail($id)
    {
        $invoice = Invoice::with([
            'items',
            'user'
        ])->find($id);
        if ($invoice == null) {
            return redirect()->route('admin.invoice.list')->withErrors('Đơn hàng không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.invoice.detail', [
            'invoice' => $invoice
        ]);
    }

    public function changeStatus(Request $request, $id)
    {
        $invoice = Invoice::with([
            'items',
            'user'
        ])->find($id);
        if ($invoice == null) {
            return redirect()->route('admin.invoice.list')->withErrors('Đơn hàng không tồn tại hoặc đã bị xóa.');
        }

        $status = $request->input('status');
        if (!in_array($status, [
            InvoiceStatus::COMPLETE,
            InvoiceStatus::PENDING,
            InvoiceStatus::CANCEL
        ])) {
            return redirect()->route('admin.invoice.detail', ['id' => $id])->withErrors('Thay đổi trạng thái không chính xác.');
        }

        $totalPrice = $invoice->items->sum(function ($item) {
            return $item->price * $item->amount;
        });

        if ($status != InvoiceStatus::CANCEL && $invoice->status == InvoiceStatus::CANCEL && $invoice->user->money < $totalPrice) {
            return redirect()->route('admin.invoice.detail', ['id' => $id])->withErrors('Số dư của thành viên không đủ để thanh toán.');
        }

        try {
            DB::beginTransaction();

            if ($status == InvoiceStatus::CANCEL && $invoice->status != InvoiceStatus::CANCEL) {
                // Nếu hủy bỏ đơn hàng thì cộng lại tiền
                $this->userRepo->addMoney($invoice->user->id, $totalPrice);

                // và xóa quyền sở hữu khóa học
                $courseIds = $invoice->items->where('type', ObjectType::COURSE)->pluck('object_id');
                UserCourse::where('user_id', $invoice->user->id)
                    ->whereIn('course_id', $courseIds)
                    ->delete();
            } else if ($status != InvoiceStatus::CANCEL && $invoice->status == InvoiceStatus::CANCEL) {
                // Nếu kích hoạt lại đơn hàng thì trừ tiền
                $this->userRepo->removeMoney($invoice->user->id, $totalPrice);

                // và khôi phục quyền sở hữu khóa học
                $courseIds = $invoice->items->where('type', ObjectType::COURSE)->pluck('object_id');
                UserCourse::withTrashed()
                    ->where('user_id', $invoice->user->id)
                    ->whereIn('course_id', $courseIds)
                    ->restore();
            }

            $invoice->status = $status;
            $invoice->save();

            DB::commit();

            return redirect()->route('admin.invoice.detail', ['id' => $id])->with('success', 'Thay đổi trạng thái thành công.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return redirect()->route('admin.invoice.detail', ['id' => $id])->withErrors('Thay đổi trạng thái thất bại.');
        }
    }
}
