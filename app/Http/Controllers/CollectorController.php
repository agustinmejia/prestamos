<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectorController extends Controller
{
    public function index()
    {
        return view('collector.browse');
    }
}