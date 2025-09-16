<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve; // ton modèle Eleve
use DB;

class statsController extends Controller
{
    public function performanceAcademique()
    {
        // éviter les MAN null
        $exclusions = Eleve::whereNotNull('MAN')->where('MAN', '<', 5)->count();
        $redoublants = Eleve::whereNotNull('MAN')->where('MAN', '>=', 5)->where('MAN', '<', 10)->count();
        $passants = Eleve::whereNotNull('MAN')->where('MAN', '>=', 10)->count();

        // si tu veux total
        $total = $exclusions + $redoublants + $passants;

        return view('pages.vitrine', compact('exclusions', 'redoublants', 'passants', 'total'));
    }
}

