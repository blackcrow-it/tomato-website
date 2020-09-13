<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UserInfoRequest;
use App\InvoiceItem;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function info()
    {
        redirect()->setIntendedUrl(route('user.info'));

        return view('frontend.user.info');
    }

    public function info_getData()
    {
        return Auth::user();
    }

    public function info_submitData(UserInfoRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = Auth::user();
            $user->fill($request->input());
            $user->save();
        });
    }

    public function invoice()
    {
        $invoiceItems = InvoiceItem::whereHas('invoice', function (Builder $query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $invoiceItems->getCollection()->transform(function($item) {
            $item->object = null;
            switch ($item->type) {
                case ObjectType::COURSE:
                    $item->object = Course::with('category')->find($item->object_id);
                    break;
            }
            return $item;
        });

        return view('frontend.user.invoice', [
            'invoice_items' => $invoiceItems
        ]);
    }
}
