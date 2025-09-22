<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InscrirepersonnelController extends Controller
{
     public function index(Request $request){  
        return view('pages.GestionPersonnel.inscrirepersonnel');
    }
}
