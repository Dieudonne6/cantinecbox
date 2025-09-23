<?php

namespace App\Http\Controllers;

use App\Models\Tprime;
use App\Models\ProfilPrime;
use App\Models\Classesgroupeclass;
use App\Models\Matieres;
use App\Models\Profil;
use App\Models\Agent;

use Illuminate\Support\Str;

use Illuminate\Http\Request;



class InscrirepersonnelController extends Controller
{
     public function index(Request $request){  
        $classes = Classesgroupeclass::all();
        $matieres = Matieres::all();
        $primes = Tprime::all();
        $profils = Profil::all();

        return view('pages.GestionPersonnel.inscrirepersonnel' , compact('classes', 'matieres', 'primes', 'profils'));
    }

   
     // Fonction pour la création de prime
    public function storePrime(Request $request)
    {
        // Conversion du type
        $typeMap = [
            'PRIME IMPOSABLE' => 'P',
            'PRIME NON IMPOSABLE' => 'I',
            'RETENUE' => 'R'
        ];

        // Conversion de la base variable
        $baseMap = [
            'SALAIRE BASE' => 'SB',
            'SALAIRE BASE + Prime' => 'ST',
            'Aucune' => 'XX'
        ];

        $type = $typeMap[$request->input('type_prime')] ?? null;
        $base = $baseMap[$request->input('base_variable')] ?? null;

        // Sauvegarde dans tprimes
        Tprime::create([
            'CODEPR'      => $request->input('code_rubrique'),
            'LIBELPR'     => $request->input('intitule_rubrique'),
            'TYPEPR'      => $type,
            'MONTANTFIXE' => $request->input('montant_fixe'),
            'MONTANTVAR'  => $request->input('montant_variable'),
            'BASEVARIABLE'=> $base,
        ]);

        // Sauvegarde dans profils_primes
        ProfilPrime::create([
            'CODEPR'      => $request->input('code_rubrique'),
            'MONTANTFIXE' => $request->input('montant_fixe'),
            'MONTANTVAR'  => $request->input('montant_variable'),
            'BASEVARIABLE'=> $base,
            'ComptePrime' => -1,
            'ChapitrePrime' => -1,
        ]);

        return back()->with('success', 'Prime créée avec succès.');
    }

    //fonction pour la création de profils

    public function store(Request $request)
    {
        // Validation simple
        $validated = $request->validate([
            'NomProfil'   => 'required|string|max:255',
            'SalaireBase' => 'required|numeric|min:0',
            'TypeImpot'   => 'required|string',
            'NbHeuresDu'  => 'nullable|numeric|min:0',
        ]);

        // Création du profil
        Profil::create([
            'NomProfil'        => $validated['NomProfil'],
            'SalaireBase'      => $validated['SalaireBase'],
            'TypeImpot'        => $validated['TypeImpot'],
            'NbHeuresDu'       => $validated['NbHeuresDu'] ?? 0,
            'CalculerCnss'     => $request->has('CalculerCnss') ? 1 : 0,
            'TauxCycle1jour'   => 0,
            'TauxCycle2jour'   => 0,
            'TauxHeureSupC1'   => 0,
            'TauxCycle1Soir'   => 0,
            'TauxCycle2Soir'   => 0,
            'TauxUniqueJour'   => 0,
            'TauxUniqueSoir'   => 0,
            'TauxHeureSupC2'   => 0,
            'TauxHeureSupUnique'=> 0,
        ]);

        return redirect()->back()->with('success', 'Profil enregistré avec succès ✅');
    }

    // Fonction pour la création d'un agent
    
    public function storeargent(Request $request)
{
    // Validation
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'required|date',
        'lieu' => 'required|string|max:255',
        'sexe' => 'required|integer',
        'nb_enfants' => 'nullable|integer|min:0',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'date_entree' => 'nullable|date',
        'poste_occupe' => 'nullable|string|max:255',
        'grade' => 'nullable|string|max:255',
        'diplome_academique' => 'nullable|string|max:255',
        'diplome_professionnel' => 'nullable|string|max:255',
        'profil' => 'nullable|integer',
        'cycle' => 'nullable|integer',
        'banque' => 'nullable|string|max:255',
        'cnss' => 'nullable|string|max:255',
        'ifu' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:255',
    ]);

    // Gestion de la photo
    $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos_agents', 'public') : null;

    // Création de l'agent
    $agent = Agent::create([
        'NOM' => $request->nom,
        'PRENOM' => $request->prenom,
        'DATENAIS' => $request->date_naissance,
        'LIEUNAIS' => $request->lieu ?? ' ',
        'NATION' => $request->nationalite ?? ' ',
        'SEXE' => (int) ($request->sexe ?? 0),
        'SITMAT' => (int) ($request->matrimoniale ?? -1),
        'PROVENANCE' => ' ',
        'CODEDEPT' => ' ',
        'POSITMILIT' => ' ',
        'NBENF' => (int) ($request->nb_enfants ?? 0),
        'DIPLOMEAC' => $request->diplome_academique ?? ' ',
        'DIPLOMEPRO' => $request->diplome_professionnel ?? ' ',
        'DATEDEP' => null,
        'DATEENT' => $request->date_entree ?? null,
        'DATEENTADM' => null,
        'DATEFONC' => null,
        'DATENOM' => null,
        'REFNOM' => ' ',
        'DATETITU' => null,
        'REFTITU' => ' ',
        'GRADE' => $request->grade ?? ' ',
        'INDICER' => ' ',
        'INDICEP' => ' ',
        'NOTE' => ' ',
        'POSTE' => $request->poste_occupe ?? ' ',
        'FONCTIONP' => ' ',
        'ADRPERS' => ' ',
        'ADRVAC' => ' ',
        'CONJOINT' => ' ',
        'LIEUCONJOINT' => ' ',
        'PREVENIR' => ' ',
        'RAISONINT' => ' ',
        'DATEDEBINT' => null,
        'ADRPREV' => ' ',
        'DATEFININT' => null,
        'CODECLAS' => ' ',
        'SIGNEP' => ' ',
        'TYPEAGENT' => ' ',
        'DATERET' => null,
        'TAUXHORAIRE' => 0,
        'SELET' => 0,
        'CYCLES' => (int) ($request->cycle ?? 0),
        'FONCTIONS' => ' ',
        'SALBASE' => 0,
        'TAUXHS' => 0,
        'NBHEURE' => 0,
        'PHOTO' => $photoPath,
        'PriseEnchargeEtat' => 0,
        'CHAPITRE' => ' ',
        'TAUXHORAIRE2' => 0,
        'NUMCNSS' => $request->cnss ?? ' ',
        'Numeroprofil' => (int) ($request->profil ?? 0),
        'LibelTypeAgent' => ' ',
        'NbHeuredu' => 0,
        'Enseignant' => $request->poste_occupe === 'Enseignant' ? 1 : 0,
        'CBanque' => $request->banque ?? ' ',
        'NCompteBanque' => $request->n_compte_banque ?? ' ',
        'IFU' => $request->ifu ?? ' ',
        'CompteAvance' => ' ',
        'ChapitreAvance' => ' ',
        'TelAgent' => $request->telephone ?? ' ',
        'guid' => Str::uuid(),
        'SiTE' => ' ',
        'anneeacademique' => ' ',
    ]);

    return redirect()->back()->with('success', 'Agent enregistré avec succès !');
}

}






