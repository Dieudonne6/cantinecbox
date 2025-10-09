<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Classe;


class EmploidutempsController extends Controller
{
    // Affiche la liste des emplois du temps
    public function configsalles( )
    {
        $classes = Classe::select('CODECLAS', 'CODESALLE', 'VOLANTE')->get();

        // Salles = valeurs uniques non nulles/vide de CODESALLE
        $salles = Classe::whereNotNull('CODESALLE')
                        ->where('CODESALLE', '!=', '')
                        ->distinct()
                        ->pluck('CODESALLE')
                        ->sort()
                        ->values();

        return view('pages.GestionPersonnel.configsalles', compact('classes', 'salles'));
    }

    public function assignClassesToSalles()
    {
        // Pour chaque classe où CODESALLE est NULL ou vide, on met CODECLAS comme salle
        Classe::where(function ($q) {
            $q->whereNull('CODESALLE')
              ->orWhere('CODESALLE', '')
              ->orWhere('CODESALLE', '0');
        })->update(['CODESALLE' => \DB::raw('CODECLAS')]);

        return redirect()->back()->with('success', 'Toutes les classes sans salle ont été associées à leur code classe.');
    }
}