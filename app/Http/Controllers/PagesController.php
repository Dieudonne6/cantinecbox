<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function inscription(){
        return view('pages.inscription');
    }
}
