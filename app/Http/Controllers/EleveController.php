<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EleveController extends Controller
{
    public function inscription(){
        return view('pages.inscriptions.inscrireeleve');
    }
    

}
