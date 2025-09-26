<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tprime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulletinPaieController extends Controller
{
    public function rubriquesalaire () {
        $rubriques = Tprime::get();
        // dd($rubriques);
        return view('pages.BulletinPaie.rubriquesalaire', compact('rubriques'));
    }


    public function enregistrerrubriquesalaire(Request $request) {
        $rubrique = new Tprime();
        $rubrique->CODEPR = $request->input('codepr');
        $rubrique->LIBELPR = $request->input('libelpr');
        $rubrique->TYPEPR = $request->input('typepr');
        $rubrique->MONTANTFIXE = $request->input('montantfixe');
        $rubrique->MONTANTVAR = $request->input('montantvar')/100;
        $rubrique->BASEVARIABLE = $request->input('basevariable');
        $rubrique->save();

        return redirect()->route('rubriquesalaire')
            ->with('status', 'Rubrique enregistrée avec succes !');

    }

    public function supprimerrubrique(Request $request) {
        $codepr = $request->input('codepr');
        DB::table('tprimes')
            ->where('codepr', $codepr)
            ->delete();

        return redirect()->route('rubriquesalaire')
        ->with('status', 'Rubrique supprimée avec succes !');
    }
}
