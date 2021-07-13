<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Part;
use App\PartTest;
use App\PartVideo;
use App\ProcessPart;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Http\Response;

class PartTestController extends Controller
{
    public function getData($id)
    {
        $part = Part::findOrFail($id);
        $data = $part->part_test;
        if ($data == null) return response('Test not found.', 500);

        return $data->data;
    }

    public function setCompletePart(Request $request)
    {
        $id = $request->input('part_id');
        $part = Part::findOrFail($id);
        $processPart = ProcessPart::where('part_id', $part->id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$processPart) {
            $processPart = new ProcessPart;
        }
        $processPart->part_id = $part->id;
        $processPart->user_id = auth()->id();
        $processPart->is_check = true;
        $processPart->save();
        return response()->json('success', Response::HTTP_OK);
    }
}
