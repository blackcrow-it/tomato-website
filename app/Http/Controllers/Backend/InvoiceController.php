<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $list = Invoice::with('items')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('backend.invoice.list', [
            'list' => $list
        ]);
    }
}
