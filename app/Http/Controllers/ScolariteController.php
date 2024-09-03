<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ClasseController;

use App\Models\Compte;
use App\Models\Classe;

class ScolariteController extends Controller
{
    //

    public function getparamcomposantes(){
        $comptes = Compte::get();

        return view ('pages.inscriptions.paramcomposantes')->with('comptes', $comptes);
    }

    public function getfacturesclasses(){

        $factures = DB::table('classes')
            ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            //  ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            ->select(
                'classes.*',
                'typeenseigne.type as typeenseigne_type',
                )
            ->get();

        return view ('pages.inscriptions.facturesclasses', compact('factures'));
    }
}
