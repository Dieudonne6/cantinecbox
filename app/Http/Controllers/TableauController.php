<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableauController extends Controller
{
    //
    public function tableauanalytique(){
        return view('pages.notes.tableauanalytique');
    }
}
