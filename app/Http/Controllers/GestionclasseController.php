<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionclasseController extends Controller
{
      public function groupes(){
        
        return view('pages.inscriptions.groupes');
    } 
}
