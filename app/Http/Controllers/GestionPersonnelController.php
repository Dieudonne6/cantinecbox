<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeAgent;
use App\Models\Agent;
use App\Models\Matieres;
use App\Models\Profmat;
use App\Models\Profil;
use App\Models\Classes;
use App\Models\Promo;

use Illuminate\Pagination\Paginator;

class GestionPersonnelController extends Controller
{

    //Création de profil pour personnel ****************************************************************************************************
    public function UpdatePersonnel(Request $request){ 
        $agents = Agent::all();
        $matieres = Matieres::all();
        $codemat = Profmat::all();

        return view('pages.GestionPersonnel.UpdatePersonnel', compact('agents', 'matieres', 'codemat'));
    }


    // Page avec tableau**************************************************************************************************************
    public function AddAgent()
    {
        // On récupère tous les types d’agents
        $agents = TypeAgent::all();
        return view('pages.GestionPersonnel.AddAgent', compact('agents'));
    }


    //Ajouter un type d’agent***********************************************************************************************************
    public function storeTypeAgent(Request $request)
    {
        $agent = TypeAgent::create([
            'LibelTypeAgent' => $request->libelle,
            'Quota' => $request->quota,
        ]);

        return response()->json([
            'LibelTypeAgent' => $agent->LibelTypeAgent,
            'Quota' => $agent->Quota,
        ]);
    }


    //Supprimer par libellé (fonctionnera sans id)**************************************************************************************
    public function deleteByLibelle($libelle)
    {
        // Récupérer l'ordre des libellés pour connaître la position (0..)
        $libelles = TypeAgent::pluck('LibelTypeAgent')->toArray();
        $pos = array_search(urldecode($libelle), $libelles, true);

        // Empêcher suppression des 4 premiers (même règle que côté frontend)
        if ($pos !== false && $pos < 4) 
        {
            return response()->json(['error' => 'Impossible de supprimer ce type'], 403);
        }

        // Supprimer via query builder (ne dépend pas du primary key)
        $deleted = TypeAgent::where('LibelTypeAgent', urldecode($libelle))->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Type introuvable'], 404);
    }

    // Mettre à jour par libellé (fonctionnera sans id)*******************************************************************************
    public function updateByLibelle(Request $request, $libelle)
    {
        // Décode et vérifie position
        $decoded = urldecode($libelle);
        $libelles = TypeAgent::pluck('LibelTypeAgent')->toArray();
        $pos = array_search($decoded, $libelles, true);

        if ($pos !== false && $pos < 4) {
            return response()->json(['error' => 'Impossible de modifier ce type'], 403);
        }

        // Update via query builder
        $newLib = $request->input('libelle', $decoded);
        $newQuota = $request->input('quota', null);

        $affected = TypeAgent::where('LibelTypeAgent', $decoded)
                    ->update([
                        'LibelTypeAgent' => $newLib,
                        'Quota' => $newQuota
                    ]);

        if ($affected) {
            return response()->json([
                'LibelTypeAgent' => $newLib,
                'Quota' => $newQuota,
            ]);
        }

        return response()->json(['error' => 'Type introuvable'], 404);
    }

    // Affichage de la vure pour configuration******************************************************************************************
    public function confTauxH() 
    {
        $profils = Profil::all();

        $classecycle1 = Classes::where('CYCLE', 1)
                                ->where('CODECLAS', '!=', 'NON')
                                ->where('CODECLAS', '!=', 'DELETE')
                                ->get();
        $classecycle2 = Classes::where('CYCLE', 2)
                                ->where('CODECLAS', '!=', 'NON')
                                ->where('CODECLAS', '!=', 'DELETE')
                                ->get();
        $codepromo1 = Classes::where('CODEPROMO', '!=', '00')
                                ->distinct()
                                ->pluck('CODEPROMO'); 
        $codepromo2 = Classes::where('CYCLE', 2)
                                ->where('CODEPROMO', '!=', '00')
                                ->distinct()
                                ->pluck('CODEPROMO'); 
        $codepromo = $codepromo1->merge($codepromo2);

        return view('pages.GestionPersonnel.confTauxH', compact('profils', 'classecycle1', 'classecycle2', 'codepromo', 'codepromo1', 'codepromo2'));
    }

    // Affichage des matières en fonctions des opérateurs*********************************************************************************
    public function getMatieres($matricule)
    {
        // Récupère tous les CODEMAT liés au matricule
        $codes = Profmat::where('MATRICULE', $matricule)->pluck('CODEMAT');

        // Va chercher les matières correspondantes
        $matieres = Matieres::whereIn('CODEMAT', $codes)->get(['CODEMAT','NOMCOURT','LIBELMAT']);

        return response()->json($matieres);
    }

    //Fonction pour la suppression d'agent*******************************************************************************************************
    public function destroy($matricule)
    {
        $agent = Agent::where('MATRICULE', $matricule)->first();
        if ($agent) {
            $agent->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    //Fonction pour la configuration des taux horaires*******************************************************************************************************
    public function updateNormales(Request $request)
    {
        $request->validate([
            'Numeroprofil' => 'required|integer|exists:profils,Numeroprofil',
        ]);

        $profil = Profil::findOrFail($request->Numeroprofil);

        $profil->update([
            'SalaireBase'     => $request->input('salaire_base'),
            'TauxCycle1jour'  => $request->input('TauxCycle1jour'),
            'TauxCycle2jour'  => $request->input('TauxCycle2jour'),
            'TauxCycle1Soir'  => $request->input('TauxCycle1Soir'),
            'TauxCycle2Soir'  => $request->input('TauxCycle2Soir'),
            'TauxUniqueJour'  => $request->input('TauxUniqueJour'),
            'TauxUniqueSoir'  => $request->input('TauxUniqueSoir'),
        ]);

        return back()->with('success', 'Heures normales mises à jour avec succès.');
    }

    public function updateSup(Request $request)
    {
        $request->validate([
            'Numeroprofil' => 'required|integer|exists:profils,Numeroprofil',
        ]);

        $profil = Profil::findOrFail($request->Numeroprofil);

        $profil->update([
            'TauxHeureSupC1'     => $request->input('TauxHeureSupC1'),
            'TauxHeureSupC2'     => $request->input('TauxHeureSupC2'),
            'TauxHeureSupUnique' => $request->input('TauxHeureSupUnique'),
        ]);

        return back()->with('success', 'Heures supplémentaires mises à jour avec succès.');
    }

}
