<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatistiquesService;
use Illuminate\Support\Facades\Cache;

class TableauController extends Controller
{
    protected $statistiquesService;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }

    public function tableauanalytique(Request $request)
    {
        // Si le formulaire n'est pas encore soumis, on affiche la vue vide
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        // Validation stricte des entrées du formulaire
        $data = $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            'typeEtat'          => 'required|in:tableau_analytique',
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales.*.max'  => 'required|numeric|min:0|max:20|gt:intervales.*.min',
        ]);

        // Vérification du type d'état (ici tableau analytique)
        if ($data['typeEtat'] !== 'tableau_analytique') {
            return response()->json(['error' => 'Type d\'état invalide.'], 400);
        }

        // Mise en cache des statistiques pour éviter les recalculs inutiles
        $cacheKey = "stats_{$data['periode']}";
        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerStatistiques($data);
        });

        // Retourner la vue avec les statistiques calculées
        return view('pages.notes.tableauanalytique', compact('resultats'));
    }
}
