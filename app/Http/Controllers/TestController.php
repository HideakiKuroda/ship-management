<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;

class TestController extends Controller
{
    public function create(){
        $tests=Test::all();
        return view('test', compact('tests'));
    }
}
