<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Compte;

class ScolariteController extends Controller
{
    //

    public function getparamcomposantes(){
        $comptes = Compte::get();

        return view ('pages.inscriptions.paramcomposantes')->with('comptes', $comptes);
    }
}
