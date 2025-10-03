<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tprime;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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


    /**
     * Met à jour une rubrique (route PUT /modifierubrique/{code}).
     */
    public function update(Request $request, $code)
    {
        $rules = [
            'libelpr' => 'required|string|min:2|max:50',
            'type' => 'required|in:P,I,R',
            'montantfixe' => 'required|numeric|min:0',
            'montantvar' => 'required|numeric|min:0',
            'basevariable' => 'nullable|in:SB,ST,',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('erreur', 'La mise à jour a échoué, corrigez les erreurs.');
        }

        try {
            $rubrique = Tprime::where('CODEPR', $code)->firstOrFail();

            $rubrique->LIBELPR = $request->input('libelpr');
            $rubrique->TYPEPR = $request->input('type');
            $rubrique->MONTANTFIXE = $request->input('montantfixe');
            $rubrique->MONTANTVAR = $request->input('montantvar');
            $rubrique->BASEVARIABLE = $request->input('basevariable') ?? '';

            $rubrique->save();

            return redirect()->back()->with('status', 'Rubrique mise à jour.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('erreur', 'Rubrique introuvable.');
        } catch (\Exception $e) {
            // \Log::error($e);
            return redirect()->back()->with('erreur', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }


    public function supprimerrubrique(Request $request) {
        $codepr = $request->input('codepr');
        DB::table('tprimes')
            ->where('codepr', $codepr)
            ->delete();

        return redirect()->route('rubriquesalaire')
        ->with('status', 'Rubrique supprimée avec succes !');
    }


    
    public function profilsagents () {
        $profils = Profil::get();
        $tprimes = Tprime::get(); // adapte les colonnes si nécessaire
        // dd($rubriques);
        return view('pages.BulletinPaie.profilsagents', compact('profils', 'tprimes'));
    }



public function getPrimes($numero)
{
    // Joins tprimes pour récupérer aussi les libellés / valeurs par défaut si besoin
    $primes = DB::table('profils_primes as pp')
        ->leftJoin('tprimes as t', 'pp.CODEPR', '=', 't.CODEPR')
        ->select(
            'pp.CODEPR',
            't.LIBELPR',
            'pp.MONTANTFIXE',
            'pp.MONTANTVAR',
            'pp.BASEVARIABLE'
            // tu peux aussi sélectionner 't.LIBEL as LIBEL' si voulu
        )
        ->where('pp.Numeroprofil', $numero)
        ->get();

    return response()->json($primes);
}

public function getAgents($numero)
{
    // adapte le nom de la table / colonnes si différent
    $agents = DB::table('agent')
        ->select('MATRICULE', 'NOM', 'PRENOM') // adapte colonnes existantes
        ->where('Numeroprofil', $numero)
        ->orderBy('NOM')
        ->get();

    return response()->json($agents);
}



    // traitement du formulaire d'ajout (POST => enregistrerprofilagents)
    public function enregistrerProfilAgents(Request $request)
    {

        // dd($request->all());

        // $rules = [
        //     'nomprofil' => 'required|string|max:100',
        //     'salairebase' => 'required|numeric|min:0',
        //     'nbheuresdu' => 'required|integer|min:0',
        //     // les champs des primes sont validés plus bas
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with('erreur', 'Veuillez corriger les champs.');
        // }

        DB::beginTransaction();

        
        try {
            // 1) créer le profil (adapte les noms de colonnes)
            $profil = Profil::create([
                'NomProfil' => $request->input('nomprofil'),
                'SalaireBase' => $request->input('salairebase'),
                'NbHeuresDu' => $request->input('nbheuresdu'),
                'TypeImpot' => $request->input('typeimpot'),
                'TauxHeureSupUnique' => $request->input('tauxheuresupunique') ?? null,
                'TauxHeureSupC2' => $request->input('tauxheursupc2') ?? null,
                'CalculerCnss' => $request->has('calculercnss') ? 1 : 0,
                // ajoute autres colonnes si besoin
            ]);

            // 2) traitement des primes cochées
            // primes_selected[] contient CODEPR pour chaque prime cochée
            $primes_selected = $request->input('primes_selected', []); // array of CODEPR
            $inserts = [];

            foreach ($primes_selected as $codepr) {
                // on lit les inputs associés : montantfixe[CODEPR], montantvar[CODEPR], base[CODEPR]
                $montantFixe = $request->input("montantfixe.$codepr", null);
                $montantVar   = $request->input("montantvar.$codepr", null);
                $base         = $request->input("base.$codepr", null);

                // si besoin, cast/format ou validation supplémentaire
                $inserts[] = [
                    'Numeroprofil' => $profil->Numeroprofil ?? $profil->id ?? null, // adapte PK du model Profil
                    'CODEPR' => $codepr,
                    'MONTANTFIXE' => $montantFixe !== null ? $montantFixe : 0,
                    'MONTANTVAR' => $montantVar !== null ? $montantVar : 0,
                    'BASEVARIABLE' => $base ?? '',
                    'ComptePrime' => -1,
                    'ChapitrePrime' => -1,
                ];
            }

            if (!empty($inserts)) {
                DB::table('profils_primes')->insert($inserts);
            }

            DB::commit();

            return redirect()->back()->with('status', 'Profil enregistré avec ses primes sélectionnées.');

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error($e->getMessage());
            return redirect()->back()->with('erreur', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }


    // --- mise à jour du profil (route PUT /modifierprofil/{id}) ---
    public function modifierProfil(Request $request, $id)
    {
        // validation minimale (adapte si nécessaire)
        $rules = [
            'nomprofil' => 'required|string|max:150',
            'salairebase' => 'nullable|numeric|min:0',
            'nbheuresdu' => 'nullable|integer|min:0',
            'typeimpot' => 'nullable|string|max:50',
            // pas de validation spécifique pour les primes ici, mais tu peux en ajouter
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('erreur', 'Veuillez corriger les champs.');
        }

        DB::beginTransaction();
        try {
            // trouve le profil (attention au nom de la clé primaire)
            $profil = Profil::where('Numeroprofil', $id)->first();
            if (!$profil) {
                return redirect()->back()->with('erreur', "Profil introuvable (id={$id}).");
            }

            // met à jour les champs du profil
            $profil->NomProfil = $request->input('nomprofil');
            $profil->SalaireBase = $request->input('salairebase');
            $profil->NbHeuresDu = $request->input('nbheuresdu');
            $profil->TypeImpot = $request->input('typeimpot');
            $profil->TauxHeureSupUnique = $request->input('tauxheuresupunique') ?? null;
            $profil->TauxHeureSupC2 = $request->input('tauxheursupc2') ?? null;
            $profil->CalculerCnss = $request->has('calculercnss') ? 1 : 0;

            $profil->save();

            // Synchroniser les primes : supprimer anciennes liaisons puis insérer les cochées
            DB::table('profils_primes')->where('Numeroprofil', $profil->Numeroprofil)->delete();

            $primes_selected = $request->input('primes_selected', []);
            $inserts = [];

            foreach ($primes_selected as $codepr) {
                $montantFixe = $request->input("montantfixe.$codepr", 0);
                $montantVar   = $request->input("montantvar.$codepr", 0);
                $base         = $request->input("base.$codepr", '');

                $inserts[] = [
                    'Numeroprofil' => $profil->Numeroprofil,
                    'CODEPR' => $codepr,
                    'MONTANTFIXE' => $montantFixe !== null ? $montantFixe : 0,
                    'MONTANTVAR' => $montantVar !== null ? $montantVar : 0,
                    'BASEVARIABLE' => $base ?? '',
                    'ComptePrime' => -1,
                    'ChapitrePrime' => -1,
                ];
            }

            if (!empty($inserts)) {
                DB::table('profils_primes')->insert($inserts);
            }

            DB::commit();
            return redirect()->back()->with('status', 'Profil modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('modifierProfil error: '.$e->getMessage());
            return redirect()->back()->with('erreur', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    // --- suppression du profil (route POST /supprimerprofil) ---
    public function supprimerProfil(Request $request)
    {
        // ici ton formulaire envoie 'codepr' comme hidden contenant le NumeroProfil
        $id = $request->input('codepr');
        if (!$id) {
            return redirect()->back()->with('erreur', "Identifiant du profil manquant.");
        }

        DB::beginTransaction();
        try {
            // supprimer les liaisons profils_primes
            DB::table('profils_primes')->where('Numeroprofil', $id)->delete();

            // supprimer le profil
            $deleted = Profil::where('Numeroprofil', $id)->delete();

            DB::commit();

            if ($deleted) {
                return redirect()->back()->with('status', 'Profil supprimé avec succès.');
            } else {
                return redirect()->back()->with('erreur', 'Profil introuvable ou déjà supprimé.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('supprimerProfil error: '.$e->getMessage());
            return redirect()->back()->with('erreur', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

}
