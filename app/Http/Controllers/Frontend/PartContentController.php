<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Part;

class PartContentController extends Controller
{
    public function send(Request $request, $part_id) {
        $part = Part::find($part_id);
        error_log($request->input('content'));
        return $request->input('content');
    }
}
