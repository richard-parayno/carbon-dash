<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function filter(Request $request){
        $data = $request->all();
        return redirect('/analytics-test')->with($data);
    }
}
