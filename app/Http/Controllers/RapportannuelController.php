<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RapportannuelController extends Controller
{
    public function Rapportannuel(){
        return view('pages.notes.rapportannuel');
    }
}
