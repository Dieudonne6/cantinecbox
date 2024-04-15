<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classes;
use Illuminate\Support\Facades\Session;
class ClassesController extends Controller
{
    public function classe(){
        $eleves = Eleve::get();
        $classes = Classes::get();
        return view('pages.classes')->with('eleve', $eleves)->with('classe', $classes);
    }
    public function filterEleve($CODECLAS){
        
        $classes = Classes::get();
        $filterEleves = Eleve::where('CODECLAS', $CODECLAS)->get();
        return view('pages.filterEleve')->with("filterEleve", $filterEleves)->with('classe', $classes);
    }
}