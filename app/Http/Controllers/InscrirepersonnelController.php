<?php

namespace App\Http\Controllers;

use App\Models\Tprime;
use App\Models\ProfilPrime;
use App\Models\Classesgroupeclass;
use App\Models\Matieres;
use App\Models\Profil;
use App\Models\Agent;
use App\Models\TypeAgent;
use App\Models\Profmat;
use App\Models\Users;
use App\Models\Usercontrat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Http\Request;



class InscrirepersonnelController extends Controller
{
    //  public function index(Request $request){  
    //     $classes = Classesgroupeclass::all();
    //     $matieres = Matieres::all();
    //     $primes = Tprime::all();
    //     $profils = Profil::all();
    //     $agents = TypeAgent::all();
       

    //     return view('pages.GestionPersonnel.inscrirepersonnel' , compact('classes', 'matieres', 'primes', 'profils', 'agents'));
    // }

    public function index(Request $request, $matricule = null)
    {
        $classes = Classesgroupeclass::all();
        $matieres = Matieres::all();
        $primes = Tprime::all();
        $profils = Profil::all();
        $agents = TypeAgent::all();

        $agentData = null;
        $selectedCodes = [];

        if ($matricule) {
            $agentData = Agent::where('MATRICULE', $matricule)->first();
            if ($agentData) {
                $selectedCodes = Profmat::where('MATRICULE', $matricule)->pluck('CODEMAT')->toArray();
            }
        }

        return view('pages.GestionPersonnel.inscrirepersonnel',
            compact('classes','matieres','primes','profils','agents','agentData','selectedCodes'));
    }


    public function updateargent(Request $request, $matricule)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $agent = Agent::where('MATRICULE', $matricule)->firstOrFail();

        // Gérer upload photo (remplacer ancienne si nouvel upload)
        if ($request->hasFile('photo')) {
            if ($agent->PHOTO) Storage::disk('public')->delete($agent->PHOTO);
            $photoPath = $request->file('photo')->store('photos_agents', 'public');
        } else {
            $photoPath = $agent->PHOTO;
        }

        $agent->update([
            'NOM' => $request->nom,
            'PRENOM' => $request->prenom,
            'DATENAIS' => $request->date_naissance,
            'LIEUNAIS' => $request->lieu ?? ' ',
            'NATION' => $request->nationalite ?? ' ',
            'SEXE' => (int) ($request->sexe ?? 0),
            'SITMAT' => (int) ($request->matrimoniale ?? -1),
            'NBENF' => (int) ($request->nb_enfants ?? 0),
            'DIPLOMEAC' => $request->diplome_academique ?? ' ',
            'DIPLOMEPRO' => $request->diplome_professionnel ?? ' ',
            'DATEENT' => $request->date_entree ?? null,
            'GRADE' => $request->grade ?? ' ',
            'POSTE' => $request->poste_occupe ?? ' ',
            'CODECLAS' => $request->principal_classe ?? ' ',
            'CYCLES' => (int) ($request->cycle ?? 0),
            'PHOTO' => $photoPath,
            'NUMCNSS' => $request->cnss ?? ' ',
            'Numeroprofil' => (int) ($request->profil ?? 0),
            'LibelTypeAgent' => $request->LibelTypeAgent ?? ' ',
            'Enseignant' => $request->poste_occupe === 'Enseignant' ? 1 : 0,
            'CBanque' => $request->banque ?? ' ',
            'IFU' => $request->ifu ?? ' ',
            'TelAgent' => $request->telephone ?? ' ',
        ]);

        // Mettre à jour Profmat : supprimer anciens puis insérer nouveaux si fournis
        Profmat::where('MATRICULE', $matricule)->delete();
        if ($request->has('code')) {
            foreach ($request->code as $code) {
                if (!empty($code)) {
                    Profmat::create([
                        'CODEMAT' => $code,
                        'MATRICULE' => $matricule,
                    ]);
                }
            }
        }

        return redirect()->route('inscrirepersonnel.index', $matricule)
                        ->with('success', 'Agent mis à jour avec succès !');
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
    
    // public function storeargent(Request $request)
    // {
    //     // Validation
    //     $request->validate([
    //         'nom' => 'required|string|max:255',
    //         'prenom' => 'required|string|max:255',
    //         'date_naissance' => 'required|date',
    //         'lieu' => 'required|string|max:255',
    //         'sexe' => 'required|integer',
    //         'nb_enfants' => 'nullable|integer|min:0',
    //         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'date_entree' => 'nullable|date',
    //         'poste_occupe' => 'nullable|string|max:255',
    //         'grade' => 'nullable|string|max:255',
    //         'diplome_academique' => 'nullable|string|max:255',
    //         'diplome_professionnel' => 'nullable|string|max:255',
    //         'profil' => 'nullable|integer',
    //         'cycle' => 'nullable|integer',
    //         'banque' => 'nullable|string|max:255',
    //         'cnss' => 'nullable|string|max:255',
    //         'ifu' => 'nullable|string|max:255',
    //         'telephone' => 'nullable|string|max:255',
    //         'LibelTypeAgent' => 'nullable|string|max:255',
    //         'principal_classe' => 'nullable|string|max:20',
    //     ]);

    //     // Gestion de la photo
    //     $photoPath = $request->hasFile('photo') 
    //         ? $request->file('photo')->store('photos_agents', 'public') 
    //         : null;

    //     // Création de l'agent
    //     $agent = Agent::create([
    //         'NOM' => $request->nom,
    //         'PRENOM' => $request->prenom,
    //         'DATENAIS' => $request->date_naissance,
    //         'LIEUNAIS' => $request->lieu ?? ' ',
    //         'NATION' => $request->nationalite ?? ' ',
    //         'SEXE' => (int) ($request->sexe ?? 0),
    //         'SITMAT' => (int) ($request->matrimoniale ?? -1),
    //         'NBENF' => (int) ($request->nb_enfants ?? 0),
    //         'DIPLOMEAC' => $request->diplome_academique ?? ' ',
    //         'DIPLOMEPRO' => $request->diplome_professionnel ?? ' ',
    //         'DATEENT' => $request->date_entree ?? null,
    //         'GRADE' => $request->grade ?? ' ',
    //         'POSTE' => $request->poste_occupe ?? ' ',
    //         'CODECLAS' => $request->principal_classe ?? ' ',   
    //         'CYCLES' => (int) ($request->cycle ?? 0),
    //         'PHOTO' => $photoPath,
    //         'NUMCNSS' => $request->cnss ?? ' ',
    //         'Numeroprofil' => (int) ($request->profil ?? 0),
    //         'LibelTypeAgent' => $request->LibelTypeAgent ?? ' ', 
    //         'Enseignant' => $request->poste_occupe === 'Enseignant' ? 1 : 0,
    //         'CBanque' => $request->banque ?? ' ',
    //         'IFU' => $request->ifu ?? ' ',
    //         'TelAgent' => $request->telephone ?? ' ',
    //         'guid' => Str::uuid(),
    //     ]);

    //     // Création automatique du compte utilisateur
    //     Users::create([
    //         'nomgroupe'       => $request->LibelTypeAgent ?? ' ',
    //         'login'           => $agent->NOM, // identifiant basé sur matricule
    //         'nomuser'         => $agent->NOM,
    //         'prenomuser'      => $agent->PRENOM,
    //         'administrateur'  => ($request->profil == 'Admin') ? 1 : 0,
    //         'motdepasse'      => Hash::make('1234'), // mot de passe par défaut
    //         'user_actif'      => 1,
    //         'date_desactivation' => null,
    //         'date_change_mp'  => now(), // date de création initiale
    //         'frequence_mp'    => 90, // ex: changement obligatoire tous les 90 jours
    //         'saisir_mp'       => 1,  // doit saisir mot de passe à la première connexion
    //     ]);

    //     $last = Agent::latest('MATRICULE')->first();
    //     //dd($last);
    //     if ($request->has('code')) {    
    //     foreach ($request->code as $code) {
    //         if (!empty($code)) {
    //             Profmat::create([
    //                 'CODEMAT' => $code,
    //                 'MATRICULE' => $agent->MATRICULE, 
    //             ]);
    //         }
    //     }
    // }

    //     return redirect()->back()->with('success', 'Agent enregistré avec succès !');
    // }

    public function storeargent(Request $request)
    {
        // Génération du matricule
        $last = Agent::latest('MATRICULE')->first();
        $num = $last ? intval(substr($last->MATRICULE, -4)) + 1 : 1;
        $matricule = 'MAT' . date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        // Photo
       $photoPath = $request->hasFile('photo') 
        ? $request->file('photo')->store('photos_agents', 'public') 
        : 'default.png';

        if ($request->has('auto')) {
            // Mode automatique
            $last = Agent::latest('MATRICULE')->first();
            $num = $last ? intval(substr($last->MATRICULE, -4)) + 1 : 1;
            $matricule = 'MAT' . date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
        } else {
            // Mode manuel (on prend la valeur de l’input)
            $request->validate([
                'matricule' => 'required|string|max:50|unique:agent,MATRICULE',
            ]);
            $matricule = $request->matricule;
        }

        // Création de l’agent
        $agent = Agent::create([
            'MATRICULE' => $matricule,
            'NOM' => $request->nom,
            'PRENOM' => $request->prenom,
            'DATENAIS' => $request->date_naissance,
            'LIEUNAIS' => $request->lieu ?? ' ',
            'NATION' => $request->nationalite ?? ' ',
            'SEXE' => (int) ($request->sexe ?? 0),
            'SITMAT' => (int) ($request->matrimoniale ?? -1),
            'NBENF' => (int) ($request->nb_enfants ?? 0),
            'DIPLOMEAC' => $request->diplome_academique ?? ' ',
            'DIPLOMEPRO' => $request->diplome_professionnel ?? ' ',
            'DATEENT' => $request->date_entree ?? null,
            'GRADE' => $request->grade ?? ' ',
            'POSTE' => $request->poste_occupe ?? ' ',
            'CODECLAS' => $request->principal_classe ?? ' ',   
            'CYCLES' => (int) ($request->cycle ?? 0),
            'PHOTO' => $photoPath,
            'NUMCNSS' => $request->cnss ?? ' ',
            'Numeroprofil' => (int) ($request->profil ?? 0),
            'LibelTypeAgent' => $request->LibelTypeAgent ?? ' ', 
            'Enseignant' => $request->poste_occupe === 'Enseignant' ? 1 : 0,
            'CBanque' => $request->banque ?? ' ',
            'IFU' => $request->ifu ?? ' ',
            'TelAgent' => $request->telephone ?? ' ',
            'guid' => Str::uuid(),
        ]);

        // Création automatique du compte utilisateur
        Users::create([
            'nomgroupe'       => $request->poste_occupe ?? ' ',
            'login'           => $matricule, // identifiant basé sur matricule
            'nomuser'         => $request->nom,
            'prenomuser'      => $request->prenom,
            'administrateur'  => ($request->profil == 'Admin') ? 1 : 0,
            'motdepasse'      => Hash::make('1234'), // mot de passe par défaut
            'user_actif'      => 1,
            'date_desactivation' => null,
            'date_change_mp'  => now(), // date de création initiale
            'frequence_mp'    => 90, // ex: changement obligatoire tous les 90 jours
            'saisir_mp'       => 1,  // doit saisir mot de passe à la première connexion
        ]);

        // Création utilisateur (table usercontrat)
        Usercontrat::create([
            'nom_usercontrat'     => $request->nom,
            'prenom_usercontrat'  => $request->prenom,
            'login_usercontrat'   => $matricule,
            'password_usercontrat'=> Hash::make('1234'),
            'statut_usercontrat'  => 1,
        ]);

        // Attribution des matières si enseignant
        if ($request->has('code')) {
            foreach ($request->code as $code) {
                if (!empty($code)) {
                    Profmat::create([
                        'CODEMAT' => $code,
                        'MATRICULE' => $agent->MATRICULE, 
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', "Agent enregistré avec succès ! Matricule : $matricule et utilisateur créé.");
    }

}






