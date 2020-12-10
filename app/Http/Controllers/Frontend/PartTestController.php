<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Part;
use App\PartTest;
use App\PartVideo;
use Illuminate\Http\Request;
use Storage;

class PartTestController extends Controller
{
    public function getData($id)
    {
        $part = Part::findOrFail($id);
        $data = $part->part_test;
        if ($data == null) return response('Test not found.', 500);

        return $data->data;
    }
}
