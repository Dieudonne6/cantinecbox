<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;

class EditionController extends Controller
{
    public function editions(){
    
        $eleves = Eleve::where('EcheancierPerso', 1)
        ->orderBy('CODECLAS')
        ->get();
        return view('pages.inscriptions.editions', compact('eleves'));
      } 
   
}

