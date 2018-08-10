<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;

class ExtController extends Controller
{
    public function show()
    {
        return view('ext');
    }

    public function update(Request $request)
    {
        return Test::where('id', $request->id)->update(['type_lot' => $request->type_lot]);
    }

    public function getTest(Request $request)
    {
        $test = new Test;
        $result = $test::all()->count();
        return '{result:'.$result.',rows:'.json_encode($test->getAllData($request, $request->start)->all(), JSON_UNESCAPED_UNICODE).'}';
//        return '{result:'.$result.',rows:'.json_encode($test->getAllData(), JSON_UNESCAPED_UNICODE).'}';
    }

    public function create(Request $request)
    {
        return 'Y';
    }
}
