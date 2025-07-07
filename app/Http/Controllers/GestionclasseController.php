<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Classesgroupeclass;
use App\Models\Groupeclasse;
use App\Models\Classes;
use App\Models\Eleve;
use App\Models\Elevea;
use App\Models\Serie;
use App\Models\Faute;
use App\Models\Tfautes;
use App\Models\Matieres;
use App\Models\Absence;
use App\Models\Typeclasse;
use App\Models\Params2;
use App\Models\Typeenseigne;
use App\Models\Eleveplus;
use App\Models\Echeancec;
use App\Models\Echeance;
use App\Models\Paramcontrat;
use Carbon\Carbon;
use App\Http\Requests\inscriptionEleveRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;



use App\Models\Promo;
use App\Models\Notes;
use App\Models\Scolarite;
// use App\Models\Echeance;

class GestionclasseController extends Controller
{
    public function groupes(){
        $listegroupe = Groupeclasse::all();

        // $listeclasse = Classesgroupeclass::where('LibelleGroupe', '=', $libelle);
        // dd($listegroupe);
        return view('pages.inscriptions.groupes')->with('listegroupe' , $listegroupe);
    }

    public function ClassesParGroupe($libelle)
    {
        try {
            // Supposons que votre table ClasseGroupeClass a une colonne 'LibelleGroupe'
            $classes = Classesgroupeclass::where('LibelleGroupe', $libelle)
                              ->orderBy('id', 'desc')
                              ->get();

            // $classes = Classesgroupeclass::where('LibelleGroupe', $libelle)->get();

            // Vérifiez les données récupérées
            if ($classes->isEmpty()) {
                return response()->json(['message' => 'Aucune classe trouvée pour ce groupe'], 404);
            }

            return response()->json($classes);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Erreur interne du serveur'], 500);
        }
    }

    public function afficherTouteClasse()
    {
        $classes = Classes::all();
        // dd($allClasses);
        return response()->json($classes);
    }

    public function ajouterClasse(Request $request, $libelle)
    {
        try {


            $classCode = $request->input('classCode');

            // Vérifiez si la classe existe déjà pour le groupe
            $existing = Classesgroupeclass::where('LibelleGroupe', $libelle)
                                          ->where('CODECLAS', $classCode)
                                          ->exists();

            if ($existing) {
                return response()->json(['error' => 'Cette classe est déjà associée à ce groupe.'], 409);
            }

            // Ajoutez la classe au groupe
            $classeGroupeClass = new Classesgroupeclass();
            $classeGroupeClass->LibelleGroupe = $libelle;
            $classeGroupeClass->CODECLAS = $classCode;
            $classeGroupeClass->save();

            return response()->json(['message' => 'Classe ajoutée avec succès']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Erreur interne du serveur'], 500);
        }
    }


    public function supprimerClasse(Request $request, $libelle, $id)
{
    try {
        $classeGroupeClass = Classesgroupeclass::where('id', $id)->first();

        if (!$classeGroupeClass) {
            return response()->json(['message' => 'Classe non trouvée'], 404);
        }

        $classeGroupeClass->delete();

        return response()->json(['message' => 'Classe supprimée avec succès']);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json(['message' => 'Erreur interne du serveur'], 500);
    }
}

public function supprimerGroupe($id)
{
    try {
        // Rechercher le groupe par ID
        $groupe = Groupeclasse::find($id);
        if (!$groupe) {
            return redirect()->back()->with('error', 'Groupe non trouvé');
        } 
        // Supprimer le groupe
        $groupe->delete();
        return redirect('/groupes')->with('success', 'Groupe supprimé avec succès');
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return redirect()->back()->with('error', 'Erreur interne du serveur');
    }
}

    public function groupe(){
        $allgroupes = Groupeclasse::all();
        return view('pages.inscriptions.groupe')->with('allgroupes', $allgroupes);
    } 

    public function ajoutergroupe (Request $request) {

        $nomgroupe = $request->nomgroupe;
    
        $groupeexist = Groupeclasse::where('LibelleGroupe', '=', $nomgroupe)->exists();
    
        if($groupeexist) {
            return redirect('/groupe')->with('error', 'le groupe existe deja');
        }
    
        $nouveaugroupe = new Groupeclasse();
        $nouveaugroupe->LibelleGroupe = $nomgroupe;
        $nouveaugroupe->save();
    
    
        return redirect('/groupe')->with('success', 'groupe ajouter avec success');
    }
    
    
    public function suppGroupe($id)
    {
        try {
            // Rechercher le groupe par ID
            $groupe = Groupeclasse::find($id);
    
            if (!$groupe) {
                return redirect()->back()->with('error', 'Groupe non trouvé');
            }
    
            // Supprimer le groupe
            $groupe->delete();
    
            return redirect('/groupe')->with('success', 'Groupe supprimé avec succès');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Erreur interne du serveur');
        }
    }


public function discipline(Request $request)
{
    // Récupérer toutes les fautes
    $fautes = Faute::all();
    
        // Récupérer toutes les matières
        $matieres = Matieres::all();

    // Récupérer toutes les fautes dans scoracine
    $tfautes = Tfautes::all();

    // Récupérer toutes les absences
    $absences = Absence::all();

    // Récupérer tous les groupes de la table classes_groupeclasse
    $classesGroupeclasse = Classesgroupeclass::select('LibelleGroupe', 'CODECLAS')->distinct()->get();

    // dd($classesGroupeclasse);
    // Si un CODECLAS est sélectionné, récupérer les élèves correspondants
    $eleves = [];
    if ($request->has('groupe')) {
        $groupe = $request->input('groupe');
        // $eleves = Eleve::where('CODECLAS', $codeclas)->get();



        // Récupérer toutes les classes appartenant au groupe sélectionné
        $classes = Classesgroupeclass::where('LibelleGroupe', $groupe)->pluck('CODECLAS'); // Suppose que GROUPE est l'attribut du groupe
        // dd($classes);
        // Récupérer les élèves qui appartiennent à toutes les classes du groupe
        $eleves = Eleve::whereIn('CODECLAS', $classes)->get();
    }

    // Passer toutes les données à la vue
    return view('pages.inscriptions.discipline', compact('fautes', 'tfautes', 'absences', 'classesGroupeclasse', 'eleves', 'matieres'));
}

public function showFaults($MATRICULE)
{
    // Récupérer l'élève par son matricule
    $eleve = Eleve::where('MATRICULE', $MATRICULE)->first();

    // Vérifiez si l'élève existe
    if (!$eleve) {
        return redirect()->back()->with('error', 'Élève non trouvé.');
    }

    // Récupérer les fautes associées à cet élève
    $fautes = $eleve->fautes; // Assurez-vous que la relation est définie dans le modèle Eleve

    // Retourner la vue avec les données de l'élève et les fautes
    return view('pages.inscriptions.discipline', compact('eleve', 'fautes'));
}

public function Tstore(Request $request)
{
    $data = $request->validate([
        'LibelFaute' => 'required|string|max:255',
        'Sanction_Indicative' => 'required|string|max:255',
        'Sanction_en_heure' => 'required|integer',
        'Sanction_en_points' => 'required|integer',
    ]);
    $absencess = $request->has('absence') ? 1 : 0;
    $tfaute = new Tfautes();
    $tfaute->LibelFaute = $request->input("LibelFaute");
    $tfaute->Sanction_Indicative = $request->input("Sanction_Indicative");
    $tfaute->Sanction_en_heure = $request->input("Sanction_en_heure");
    $tfaute->Sanction_en_points = $request->input("Sanction_en_points");
    $tfaute->Absence_ = $absencess;
    $tfaute->save();
/*     Tfautes::create($data); 
 */
    return redirect()->route('discipline')->with('success', 'Faute et sanction ajoutées avec succès.');
}

public function Tupdate(Request $request, $id)
{    
    try {
        $data = $request->validate([
            'LibelFaute' => 'required|string|max:255',
            'Sanction_Indicative' => 'nullable|string|max:255',
            'Sanction_en_heure' => 'required|integer',
            'Sanction_en_points' => 'required|integer',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        
        dd($e->errors());
    }
    $tfaute = Tfautes::where('idTFautes', $id)->firstOrFail();
    $tfaute->Absence_ = $request->has('absence') ? 1 : 0;
    $tfaute->update($data);
    $tfaute->save();
    return redirect()->route('discipline')->with('success', 'Faute et sanction modifiées avec succès.');
}

public function Tdestroy($id)
{
    $tfaute = Tfautes::where('idTFautes', $id)->firstOrFail();
    $tfaute->delete();

    return redirect()->route('discipline')->with('success', 'Faute supprimée avec succès.');
}

public function fautestore(Request $request)
{
    // Valider les données du formulaire
      // Créer un nouvel enregistrement dans la table fautes

      if ($request->inlineRadioOptions === 'option1') { // Si ABSENT
        Absence::create([
            'MATRICULE' => $request->eleve_id,
            'DATEOP' => $request->date_faute,
            'CODEMAT' => $request->matière,
            'MOTIF' => $request->motif,
            'ABSENT' => 1, // Valeur pour ABSENT
            'RETARD' => 0, // Valeur pour RETARD
            'HEURES' => $request->HEURE,
            'MOTIFVALALBLE' => $request->motif, // Assurez-vous que ce champ correspond
        ]);
    } elseif ($request->inlineRadioOptions === 'option2') { // Si RETARD
        Absence::create([
            'MATRICULE' => $request->eleve_id,
            'DATEOP' => $request->date_faute,
            'CODEMAT' => $request->matière,
            'MOTIF' => $request->motif,
            'ABSENT' => 0, // Valeur pour ABSENT
            'RETARD' => 1, // Valeur pour RETARD
            'HEURES' => $request->HEURE,
            'MOTIFVALALBLE' => $request->motif, // Assurez-vous que ce champ correspond
            ]);
    }
    
    $idabs = Absence::orderBy("IDABSENCE", "desc")->first();
    $idabss = $idabs->IDABSENCE;

    $faute = Faute::create([
        'MATRICULE' => $request->eleve_id,
        'DATEOP' => $request->date_faute,
        'FAUTE' => $request->faute,
        'SANCTION' => $request->sanction,
        'NBHEURE' => $request->nbheure,
        'SEMESTRE' => $request->semestre,
        'COLLECTIVE' => $request->collective,
        'IDABSENCE' => $idabss, // Valeur par défaut, à mettre à jour si nécessaire
        'idTFautes' => $request->faute, // Assurez-vous que ce champ correspond
    ]);

    // Si l'option est 'ABSENT', ajouter une entrée dans la table absences

    // Rediriger avec un message de succès
    return back()->with('success', 'Faute ajoutée avec succès.');
}

public function fauteupdate(Request $request, $id)
{
        $data = $request->validate([
            'FAUTE' => 'required|string|max:255',
            'SANCTION' => 'required|string|max:255',
            'NBHEURE' => 'required|integer',
            'COLLECTIVE' => 'required|string|max:255',
            
        ]);
        $faute = Faute::where('IDFAUTES', $id)->first();
        $faute->FAUTE = $request->input("FAUTE");
        $faute->SANCTION = $request->input("SANCTION");
        $faute->NBHEURE = $request->input("NBHEURE");
        $faute->COLLECTIVE = $request->input("COLLECTIVE");
        //$faute->update($data);
        $faute->save();
    return redirect()->route('discipline')->with('success', 'Faute et sanction modifiées avec succès.');
}

public function fautedestroy($id)
{
    $faute = Faute::where('IDFAUTES', $id)->firstOrFail();
    $faute->delete();
    
    return redirect()->route('discipline')->with('success', 'Faute supprimée avec succès.');
}

public function imprimerfautes()
{
    // Récupérer les données de la table Fautes
    $fautes = Faute::all();

    // Retourner la vue avec les fautes
    return view('pages.etat.imprimerfaute', compact('fautes'));
}

public function imprimerabsences()
{
    // Récupérer les données de la table Fautes
    $absences = Absence::all();

    // Retourner la vue avec les fautes
    return view('pages.etat.imprimerabsence', compact('absences'));
}
   
// EleveController.php
public function imprimereleveFautes($MATRICULE)
{
    // Récupérer l'élève
    $eleve = Eleve::find($MATRICULE);
    
    // Récupérer les fautes associées à cet élève
    $fautes = Faute::where('MATRICULE', $MATRICULE)->get();

    // Si tu veux afficher une page imprimable
    return view('pages.etat.impression_fautes', compact('eleve', 'fautes'));

 
}

public function imprimereleveAbsence($MATRICULE)
{
    // Récupérer l'élève
    $eleve = Eleve::find($MATRICULE);
    
    // Récupérer les fautes associées à cet élève
    $absences = Absence::where('MATRICULE', $MATRICULE)->get();

    // Si tu veux afficher une page imprimable
    return view('pages.etat.impression_absences', compact('eleve', 'absences'));

    // Ou si tu veux générer un PDF (nécessite une librairie comme domPDF ou snappy)
    // $pdf = PDF::loadView('pages.eleves.impression_fautes', compact('eleve', 'fautes'));
    // return $pdf->download('fautes_eleve_'.$eleve->nom.'.pdf');
}
    public function series(Request $request)
    {
        $series = Serie::get();
        return view('pages.inscriptions.series')->with('series', $series);
    }

    public function saveserie(inscriptionEleveRequest $request)
    {
        $series = new Serie();
        $series->SERIE = $request->input('SERIE');
        $series->LIBELSERIE = $request->input('LIBELSERIE');
        $series->CYCLE = $request->input('CYCLE');


                // Vérifier si la SERIE existe déjà
        if (Serie::where('SERIE', $series->SERIE)->exists()) {
            return back()->with('error', 'Cette série existe déjà.')->withInput();
        }

            // Vérifier si le LIBELSERIE existe déjà
        if (Serie::where('LIBELSERIE', $series->LIBELSERIE)->exists()) {
        return back()->with('error', 'Ce libellé de série existe déjà.')->withInput();
        }

        $series->save();
        return back()->with('status', 'Enregistré avec succès');
    }

    public function updateserie(Request $request)
    {
        $series = Serie::find($request->SERIE); // Using SERIE as the identifier
        if ($series) {
            $series->LIBELSERIE = $request->input('LIBELSERIE');
            $series->CYCLE = $request->input('CYCLE');
            $series->save();

            return back()->with('status', 'Modifié avec succès');
        }

        return back()->withErrors('Erreur lors de la modification.');
    }

    public function deleteserie(Request $request)
    {
        $deleteserie = Serie::find($request->SERIE); // Using SERIE as the identifier
        if ($deleteserie) {
            $deleteserie->delete();
            return back()->with('status', 'Supprimé avec succès');
        }

        return back()->withErrors('Erreur lors de la suppression.');
    }
    public function deleteclass(Request $request)
    {
        $deleteclas = Classes::find($request->CODECLAS); // Using SERIE as the identifier
        if ($deleteclas) {
            $deleteclas->delete();
            return back()->with('status', 'Supprimé avec succès');
        }

        return back()->withErrors('Erreur lors de la suppression.');
    }

  
  public function savetypeclasse(inscriptionEleveRequest $request){
    $typeclas = $request->input('TYPECLASSE');
    $typeclas = Typeclasse::where('TYPECLASSE', '=', $typeclas)->exists();
    $typeclaslib = $request->input('LibelleType');
    $typeclaslib = Typeclasse::where('LibelleType', '=', $typeclaslib)->exists();

    if($typeclas) {
        return back()->with('error', 'Le code groupe existe déja')->withInput();
    } 
    if($typeclaslib) {
        return back()->with('error', 'Le libellé groupe existe déja')->withInput();
    } 
   

    $typeclasse = new Typeclasse();

    if(strtolower($request->input('LibelleType')) == "systeme"){
      $typeclasse->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclasse->LibelleType = $request->input('LibelleType');
      $typeclasse->OuiNonScolarite = 0;
      $typeclasse->OuiNonNotes = 0;
      $typeclasse->OuiNonStat = 0;
    } else {
      $typeclasse->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclasse->LibelleType = $request->input('LibelleType');
      $typeclasse->OuiNonScolarite = 1;
      $typeclasse->OuiNonNotes = 1;
      $typeclasse->OuiNonStat = 1;
    }
    $typeclasse->save();
    return back()->with('status','Enregistrer avec succes');
  }
  public function getclasse(){
    $allclasse = Typeclasse::get();
    return view('pages.inscriptions.typesclasses')->with('allclasse', $allclasse);   
  }
  public function updateTypeClasse(Request $request)
  {
    $typeclass = Typeclasse::find($request->idtype);
    if ($typeclass) {
      $typeclass->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclass->LibelleType = $request->input('LibelleType');
      $typeclass->save();
      return back()->with('status', 'Modifié avec succès');
    }
    return back()->withErrors('Erreur lors de la modification.');
  }
  public function deletetype(Request $request)
  {
    $deletetyp = Typeclasse::find($request->idtype);
    if ($deletetyp) {
      $deletetyp->delete();
      return back()->with('status', 'Supprimé avec succès');
    } 
    return back()->withErrors('Erreur lors de la suppression.');
  }

  
   public function enregistrerclasse(inscriptionEleveRequest $request){
    $enreclas = $request->input('nomclasse');
    $enreclas = Classes::where('CODECLAS', '=', $enreclas)->exists();
    $enreclaslib = $request->input('libclasse');
    $enreclaslib = Classes::where('LIBELCLAS', '=', $enreclaslib)->exists();

    if($enreclas) {
        return back()->with('error', 'Le nom de la classe existe déja')->withInput();
    } 
    if ($enreclaslib){
        return back()->with('error', 'Le libellé de la classe existe déja')->withInput();
    }

    // Récupérer les données des échéances envoyées sous forme de JSON
    $echeancesData = $request->input('echeancesData');
    $echeances = json_decode($echeancesData, true);

    // Récupérer les données initiales de paramsgeneraux envoyées sous forme de JSON
    $infoIinitiale = $request->input('infoIinitiale');
    $infoIni = json_decode($infoIinitiale, true);

    // dd($infoIni[0]);
    if ($echeances === null) {
        // PAS D'ECHEANCE DONC ON ENREGISTRE SEULEMENT LES DONNE DE LA CLASSE ET DE PARAM GENERAUX
        // dd('null ()');
        $enrclasse = new Classes();
        $enrclasse->CODECLAS = $request->input('nomclasse');
        $enrclasse->LIBELCLAS = $request->input('libclasse');
        $enrclasse->TypeCours = $request->input('typecours');
        $enrclasse->TYPECLASSE = $request->input('typclasse');
        $enrclasse->CYCLE = $request->input('cycle');
        $enrclasse->SERIE = $request->input('typeserie');
        $enrclasse->CODEPROMO = $request->input('typepromo');
        $enrclasse->Niveau = $request->input('numero');
        $enrclasse->TYPEENSEIG = $request->input('typeensei');

        // infos de param generaux 
        $enrclasse->APAYER = $infoIni[0]['apayer'];
        $enrclasse->APAYER2 = $infoIni[0]['apayer'];

        $enrclasse->FRAIS1 = $infoIni[0]['frais1'];
        $enrclasse->FRAIS1_A = $infoIni[0]['frais1'];

        $enrclasse->FRAIS2 = $infoIni[0]['frais2'];
        $enrclasse->FRAIS2_A = $infoIni[0]['frais2'];

        $enrclasse->FRAIS3 = $infoIni[0]['frais3'];
        $enrclasse->FRAIS3_A = $infoIni[0]['frais3'];

        $enrclasse->FRAIS4 = $infoIni[0]['frais4'];
        $enrclasse->FRAIS4_A = $infoIni[0]['frais4'];

        $enrclasse->save();
    } else {


    // LES ECHEANCES EXISTENT DONC ON ENREGISTRE LES DONNE DE LA CLASSE ET CELLES DES TABLEAUX OU ECHEANCE DANS ECHEANCEC

    // dd('different de null OU ');

    $nouvclasse = new Classes();
    $nouvclasse->CODECLAS = $request->input('nomclasse');
    $nouvclasse->LIBELCLAS = $request->input('libclasse');
    $nouvclasse->TypeCours = $request->input('typecours');
    $nouvclasse->TYPECLASSE = $request->input('typclasse');
    $nouvclasse->CYCLE = $request->input('cycle');
    $nouvclasse->SERIE = $request->input('typeserie');
    $nouvclasse->CODEPROMO = $request->input('typepromo');
    $nouvclasse->Niveau = $request->input('numero');
    $nouvclasse->TYPEENSEIG = $request->input('typeensei');

    $nouvclasse->DUREE = $request->input('nbEcheances');
    $nouvclasse->PERIODICITE = $request->input('periodicite');
    $nouvclasse->DATEDEB = $request->input('dateDebut');
    $nouvclasse->TYPEECHEANCIER = $request->input('flexRadioDefault');

    $nouvclasse->APAYER = $request->input('APAYER');
    $nouvclasse->APAYER2 = $request->input('APAYER2');

    $nouvclasse->FRAIS1 = $request->input('FRAIS1');
    $nouvclasse->FRAIS1_A = $request->input('FRAIS1_A');

    $nouvclasse->FRAIS2 = $request->input('FRAIS2');
    $nouvclasse->FRAIS2_A = $request->input('FRAIS2_A');

    $nouvclasse->FRAIS3 = $request->input('FRAIS3');
    $nouvclasse->FRAIS3_A = $request->input('FRAIS3_A');

    $nouvclasse->FRAIS4 = $request->input('FRAIS4');
    $nouvclasse->FRAIS4_A = $request->input('FRAIS4_A');

    $nouvclasse->save();

    // ENREGISTREMENT DANS ECHEANCEC

        // Supprimer les lignes exixtantes dans echeancec pour cette classe
        $classe = $request->input('nomclasse'); // Récupérer la classe concernée à partir de la requête

        // dd($classe);
        // Vérifier si des lignes existent déjà pour cette classe
        $existeLignes = DB::table('echeancc')->where('CODECLAS', $classe)->exists();

        if ($existeLignes) {
            // Si des lignes existent pour cette classe, on les supprime
            DB::table('echeancc')->where('CODECLAS', $classe)->delete();
        }

        foreach ($echeances as $echeance) {
            $dateFormat = 'd/m/Y'; // Le format d'origine de la date
            $dateOriginal = $echeance['date_paiement'];
            // dd($dateOriginal);
            // Convertir la date en format Carbon
            $date = Carbon::createFromFormat($dateFormat, $dateOriginal);

            // Reformater la date au format souhaité 'Y-m-d'
            $dateFormater = $date->format('Y-m-d');

            // Insérer chaque ligne dans la base de données ou la traiter comme vous le souhaitez
            DB::table('echeancc')->insert([
                'NUMERO' => $echeance['tranche'],
                'FRACTION1' => $echeance['pourcentage_nouveau'],
                'FRACTION2' => $echeance['pourcentage_ancien'],
                'APAYER' => $echeance['montant_nouveau'],
                'APAYER2' => $echeance['montant_ancien'],
                'DATEOP' => $dateFormater,
                'DATEOP2' => $dateFormater,
                'CODECLAS' => $echeance['classe'],
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

    }


    return back()->with('status','Enregistrer avec succes');
  } 
 



  public function enregistrerinfo(Request $request){
    $enreninfo = new Eleveplus();
    $enreninfo->MATRICULE = $request->input('matricule');

    $enreninfo->maladiesconnues = $request->input('maladieschroniques');
    $enreninfo->interditalimentaires = $request->input('interditalimentaires');
    $enreninfo->groupesanguin = $request->input('groupesanguin');
    $enreninfo->electroforez = $request->input('typehemoglobine');
    $enreninfo->NOMMERE = $request->input('nommere');
    $enreninfo->prenommere = $request->input('prenommere');
    $enreninfo->telmere = $request->input('telephonemere');
    $enreninfo->emailmere = $request->input('emailmere');
    $enreninfo->professionmere = $request->input('professionmere');
    $enreninfo->adremployeurmere = $request->input('adresseemployeurmere');
    $enreninfo->adrmere = $request->input('adressepersonnellemere');
    $enreninfo->NOMPERE = $request->input('nompere');
    $enreninfo->prenompere = $request->input('prenompere');
    $enreninfo->telpere = $request->input('telephonepere');
    $enreninfo->emailpere = $request->input('emailpere');
    $enreninfo->professionpere = $request->input('professionpere');
    $enreninfo->adremployeurpere = $request->input('adresseemployeurpere');
    $enreninfo->adrpere = $request->input('adressepersonnellepere');
    $enreninfo->nomtutuer = $request->input('nomtuteur');
    $enreninfo->prenomtuteur = $request->input('prenomtuteur');
    $enreninfo->teltuteur = $request->input('telephonetuteur');
    $enreninfo->emailtuteur = $request->input('emailtuteur');
    $enreninfo->adremployeurtuteur = $request->input('adresseemployeurtuteur');
    $enreninfo->adrtuteur = $request->input('adressepersonnelletuteur');
    $enreninfo->professiontuteur = $request->input('professiontuteur');
    $enreninfo->nomurgence = $request->input('nomurgence');
    $enreninfo->prenomurgence = $request->input('prenomurgence');
    $enreninfo->telurgence = $request->input('telephoneurgence');
    $enreninfo->emailpere = $request->input('emailurgence');
    $enreninfo->adrurgence = $request->input('adressepersonnelleurgence');
    $enreninfo->autorisefilm = $request->input('autorisevideo');
    $enreninfo->autoriseuseimage = $request->input('autoriseimage');

    $enreninfo->save();
    return back()->with('status','Enregistrer avec succes');
  } 
//Promotion
public function index()
{
    $promotions = Promo::all(); // Récupère toutes les promotions
    return view('pages.inscriptions.promotions', compact('promotions')); // Utilise le bon nom de la vue
}

// Méthode store
public function store(Request $request)
{
    // Validation des champs du formulaire
    $request->validate([
        'codePromotion' => 'required|max:4',
        'libellePromotion' => 'required|max:14', // Limite ajustée pour correspondre à votre validation HTML
        'Niveau' => 'required|integer|min:1|max:7',
        'enseignement' => 'required|integer'
    ]);

    // Vérifier si le code promo existe déjà
    if (Promo::where('CODEPROMO', $request->codePromotion)->exists()) {
        return redirect()->back()
                         ->withErrors(['codePromotion' => 'Le code promotion existe déjà. Veuillez en choisir un autre.'])
                         ->withInput();
    }

    // Vérifier si le libellé de la promotion existe déjà
    if (Promo::where('LIBELPROMO', $request->libellePromotion)->exists()) {
        return redirect()->back()
                         ->withErrors(['libellePromotion' => 'Le libellé de la promotion existe déjà. Veuillez en choisir un autre.'])
                         ->withInput();
    }

    // Enregistrer la nouvelle promotion
    Promo::create([
        'CODEPROMO' => $request->codePromotion,
        'LIBELPROMO' => $request->libellePromotion,
        'Niveau' => $request->Niveau,
        'TYPEENSEIG' => $request->enseignement
    ]);
    
    // Redirection avec un message de succès
    return redirect()->route('promotions.index')->with('success', 'Promotion créée avec succès !');
}

public function update(Request $request, $codePromo)
{
    $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();

    $request->validate([
        'codePromotion' => 'required|max:4',
        'libellePromotion' => 'required|max:14', // Limite ajustée pour correspondre à votre validation HTML
        'Niveau' => 'required|integer|min:1|max:7',
        'enseignement' => 'required|integer'
    ]);

    $promotion->update([
        'CODEPROMO' => $request->codePromotion,
        'LIBELPROMO' => $request->libellePromotion,
        'Niveau' => $request->Niveau,
        'TYPEENSEIG' => $request->enseignement
    ]);

    return redirect()->route('promotions.index')->with('success', 'Promotion mise à jour avec succès !');
}


public function destroy($codePromo)
{
    $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();
    $promotion->delete();

    return redirect()->route('promotions.index')->with('success', 'Promotion supprimée avec succès !');
}


public function indexEleves()
{
    $allClass = Classes::all();
    $serie = Serie::get();
    $promotion = Promo::all();
    $typeenseigne = Typeenseigne::get();
    $typeclah = Typeclasse::get();

    // Récupérer les élèves avec leurs notes
    $eleves = Eleve::with('notes')->orderBy('nom', 'asc')->get();

    // Calcul des effectifs
    $totalEleves = $eleves->count();
    $filles = $eleves->where('SEXE', 2)->count();
    $garcons = $eleves->where('SEXE', 1)->count();
    $totalRedoublants = $eleves->where('STATUT', 1)->count();
    $fillesRedoublantes = $eleves->where('SEXE', 2)->where('STATUT', 1)->count();
    $garconsRedoublants = $eleves->where('SEXE', 1)->where('STATUT', 1)->count();

    return view('pages.inscriptions.Acceuil', compact(
        'eleves', 'allClass', 'serie', 'promotion', 'typeclah', 'typeenseigne',
        'totalEleves', 'filles', 'garcons', 'totalRedoublants', 'fillesRedoublantes', 'garconsRedoublants'
    ));
}

  //   return view('pages.inscriptions.groupes');
  // } 

//   fonction d'ajout d'un nouveau eleve

public function nouveaueleve (inscriptionEleveRequest $request) {

   
    // $imageName = $request->file('photo');
    // $imageContent = file_get_contents($imageName->getRealPath());

    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        // Le fichier a bien été uploadé et est valide
        $file = $request->file('photo');
        $imageContent = file_get_contents($file->getRealPath());
    } else {
        // Pas de photo envoyée ou fichier invalide
        $imageContent = null;  // ou gérer une valeur par défaut
    }

    // Gestion des atributs MATRICULEX et CODEWEB
    // Convertir le nouveau matricule en chaîne de caractères avec des zéros devant
    $matricule = $request->input('numOrdre');
    $formateMatricule = str_pad($matricule, 8, '0', STR_PAD_LEFT);
    // dd($formateMatricule);

    // Récupérer la valeur du checkbox de redoublant
    $redoublant = $request->has('redoublant') ? 1 : 0;

    // Recuperer la serie et le typeenseign de la classe selectionne

    $classeSelectionne = Classes::where('CODECLAS', $request->input('classe'))->first(); 
    $serieClasse = $classeSelectionne->SERIE;
    $typeenseignClasse = $classeSelectionne->TYPEENSEIG;
    $typeeClasse = $classeSelectionne->TYPECLASSE;
    $matriculePourNouveau = $request->input('numOrdre');
    $matricule = $matriculePourNouveau;
    // dd($typeeClasse);

    // Recuperation de l'arrierrer si l'eleve est un nouveau ou pas
    $typeElev = $request->input('typeEleve');
    // if ($typeElev == 1) {
    //     $arriere = 0;
    // } else {
    //     // chercher son arrierer dans la table elevesa dans scoracine
    //     $archiveEleve = Elevea::where('NOM', $request->input('nom'))
    //                     ->where('PRENOM', $request->input('prenom'))
    //                     ->first();
    //     $arriere = $archiveEleve ? $archiveEleve->MONTANTARRIERE : 0;
    // }
    // dd($arriere);

    $nouveauEleve = new Eleve();
    $nouveauEleve->CODECLAS = $request->input('classe');
    $nouveauEleve->PCLASSE = $request->input('classe');

    if(($request->input('classeEntre')) === 'idem'){
        $nouveauEleve->ANCCLASSE = $request->input('classe');
    }else{
        $nouveauEleve->ANCCLASSE = $request->input('classeEntre');
    }

    $nouveauEleve->PHOTO = $imageContent;
    $nouveauEleve->CodeReduction = 0;
    $nouveauEleve->NOM = $request->input('nom');
    $nouveauEleve->PRENOM = $request->input('prenom');
    $nouveauEleve->DATENAIS = $request->input('dateNaissance');
    $nouveauEleve->LIEUNAIS = $request->input('lieuNaissance');
    $nouveauEleve->DATEINS = $request->input('dateInscription');
    $nouveauEleve->CODEDEPT = $request->input('departement');
    $nouveauEleve->EXONERER = 2;
    $nouveauEleve->numordre = 99;
    $nouveauEleve->SEXE = $request->input('sexe');
    $nouveauEleve->SERIE = $serieClasse;
    $nouveauEleve->STATUTG = $request->input('typeEleve');
    $nouveauEleve->APTE = $request->input('aptituteSport');
    $nouveauEleve->ADRPERS = $request->input('adressePersonnelle');
    $nouveauEleve->ETABORIG = $request->input('etablissementOrigine');
    $nouveauEleve->NATIONALITE = $request->input('nationalite');
    $nouveauEleve->TYPEENSEIG = $typeenseignClasse;
    $nouveauEleve->TYPECLASSE = $typeeClasse;
    $nouveauEleve->STATUT = $redoublant;

    if ($typeElev == 1) {
        $arriere = 0;
    $nouveauEleve->ARRIERE = $arriere;
    $nouveauEleve->ARRIERE_INITIAL = $arriere;
    $nouveauEleve->MATRICULE = $matricule;
    } else {
        // chercher son arrierer dans la table elevea dans scoracine
        $archiveEleve = Elevea::where('NOM', $request->input('nom'))
                        ->where('PRENOM', $request->input('prenom'))
                        ->first();
        $arriere = $archiveEleve ? $archiveEleve->MONTANTARRIERE : 0;
        $matricule = $archiveEleve ? $archiveEleve->MATRICULE : $matriculePourNouveau;
        $nouveauEleve->ARRIERE = $arriere;
        $nouveauEleve->ARRIERE_INITIAL = $arriere;
        $nouveauEleve->MATRICULE = $matricule;

    }

    $nouveauEleve->NOMPERE = $request->input('nomPere');
    $nouveauEleve->NOMMERE = $request->input('nomMere');
    $nouveauEleve->ADRPAR = $request->input('adressesParents');
    $nouveauEleve->AUTRE_RENS = $request->input('autresRenseignements');
    $nouveauEleve->indicatif1 = $request->input('indicatifParent');
    $nouveauEleve->TEL = $request->input('telephoneParent'); // TELEPHONE PARRENT
    $nouveauEleve->TelEleve = $request->input('telephoneEleve'); // telephone eleve
    $nouveauEleve->MATRICULEX = $formateMatricule; // VALEUR GENERER DYNAMIQUEMENT
    $nouveauEleve->CODEWEB = $formateMatricule; // MEME VALEUR QUE MATRICULEX

    $nouveauEleve->save();

    $infoclasse = Classes::where('CODECLAS', ($request->input('classe')))->first();
    // $TYPEENSEIG = $infoclasse->TYPEENSEIG;
    // $TYPECLASSE = $infoclasse->TYPECLASSE;
    // $SERIE = $infoclasse->SERIE;
    // dd($SERIE);

    // $infoeleve = Eleve::where('MATRICULE', ($request->input('numOrdre')))->first();
    $infoeleve = Eleve::where('MATRICULE', $matricule)->first();
    // $infoeleve->TYPEENSEIG = $TYPEENSEIG;
    // $infoeleve->TYPECLASSE = $TYPECLASSE;
    // $infoeleve->SERIE = $SERIE;
    // $infoeleve->save();

    $echeanceClasse = Echeancec::where('CODECLAS', $request->input('classe'))->get();
    $infoparamcontrat = Paramcontrat::first();
    $anneencours = $infoparamcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours.'-'.$annesuivante;
    Echeance::where('MATRICULE', $matricule)->delete();

    // dd($echeanceClasse);
    // dd($infoclasse);
    if(($request->input('typeEleve')) == 2) {
        $infoeleve->APAYER = $infoclasse->APAYER2;
        $infoeleve->FRAIS1 = $infoclasse->FRAIS1_A;
        $infoeleve->FRAIS2 = $infoclasse->FRAIS2_A;
        $infoeleve->FRAIS3 = $infoclasse->FRAIS3_A;
        $infoeleve->FRAIS4 = $infoclasse->FRAIS4_A;
        $infoeleve->save();

        // Enregistrer les donne dans echeance
        $montantInitial = $arriere; // Le montant que tu veux mettre sur la première ligne
        $isFirst = true;

        foreach ($echeanceClasse as $index => $echeanceData) {
            if ($isFirst) {
                $montantAPayer = $montantInitial;
                $isFirst = false; // Après la première ligne, changer l'état du drapeau
              } else {
                $montantAPayer = 0; // Mettre 0 pour les autres lignes
              }
            Echeance::create([
                'MATRICULE' => $matricule,
                'NUMERO' => $echeanceData['NUMERO'], // Numérotation des échéances
                'APAYER' => $echeanceData['APAYER2'],
                'ARRIERE' => $montantAPayer, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                'DATEOP' => $echeanceData['DATEOP2'], // Date spécifique de l'échéance,
                'anneeacademique' => $annescolaire,
            ]);

        }


    } else {
        $infoeleve->APAYER = $infoclasse->APAYER;
        $infoeleve->FRAIS1 = $infoclasse->FRAIS1;
        $infoeleve->FRAIS2 = $infoclasse->FRAIS2;
        $infoeleve->FRAIS3 = $infoclasse->FRAIS3;
        $infoeleve->FRAIS4 = $infoclasse->FRAIS4;
        $infoeleve->save();

        // Enregistrer les donne dans echeance
        foreach ($echeanceClasse as $index => $echeanceData) {

            Echeance::create([
                'MATRICULE' => $matricule,
                'NUMERO' => $echeanceData['NUMERO'], // Numérotation des échéances
                'APAYER' => $echeanceData['APAYER'],
                'ARRIERE' => 0, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                'DATEOP' => $echeanceData['DATEOP'], // Date spécifique de l'échéance,
                'anneeacademique' => $annescolaire,
            ]);

        }

    }


    return redirect()->route('inscrireeleve')->with('status', 'Élève enregistré avec succès');

}

  // public function gettableclasses(){

  //   $classes = new Classes();
  //   $classes->CODECLAS = $request->input('CODECLAS');
  //   $classes->LIBELCLAS = $request->input('LIBELCLAS');
  //   $classes->TYPECLASSE = $request->input('TYPECLASSE');
  //   $classes->CODEPROMO = $request->input('CODEPROMO');
  //   $classes->CYCLE = $request->input('CYCLE');
  //   $classes->SERIE = $request->input('SERIE');
  //   $classes->TYPEENSEIG = $request->input('TYPEENSEIG');
  //   $classes->EFFECTIF = $request->input('EFFECTIF');

  //   $series->save();

  //   return view('pages.inscriptions.tabledesclasses')->with('status', 'Enregistrer avec succès');
  // }

  public function gettabledesclasses(){
    // $classes = Classe::with('serie')->get();

    $classes = DB::table('classes')
            ->join('series', 'classes.SERIE', '=', 'series.SERIE')
            ->join('typeclasses', 'classes.TYPECLASSE', '=', 'typeclasses.TYPECLASSE')
            ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            //  ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            ->select(
                'classes.*',
                'series.LIBELSERIE as serie_libelle',
                'typeclasses.LibelleType as typeclasse_LibelleType',
                'typeenseigne.type as typeenseigne_type',
                //  'typeenseigne.idenseign as typeenseigne_type'
            )
            ->get();

 
    // dd($idserie);

    return view('pages.inscriptions.tabledesclasses', compact('classes'));

  }
  public function enrclasse(){
    // if(Session::has('account')){
    // $duplicatafactures = Duplicatafacture::all();
    $typecla = Typeclasse::get();
    $serie = Serie::get();
    $promo = Promo::get();
    $infoParamGeneraux = Params2::first();
    $typeenseigne = Typeenseigne::get();





    return view('pages.inscriptions.enregistrementclasse')->with('typecla', $typecla)->with('serie', $serie)->with('promo', $promo)->with('typeenseigne', $typeenseigne)->with('infoParamGeneraux', $infoParamGeneraux);
    // } 
    // return redirect('/');

  }


//   ---------------


  public function detailfacnouvelleclasse(Request $request) {
        // Récupérer les données des échéances envoyées sous forme de JSON
        $echeancesData = $request->input('echeancesData');
        $echeances = json_decode($echeancesData, true);
    
        dd($echeances);
  }



// ---------------





  public function modifierclasse($CODECLAS){
  $typeclah = Typeclasse::get();
  $serie = Serie::get();
  $promo = Promo::get();
  $typeenseigne = Typeenseigne::get();
  $typecla = Classes::where('CODECLAS', $CODECLAS)->first();
    if (!$typecla) {
        abort(404, 'Classe non trouvée');
    }
    return view('pages.inscriptions.modifierclasse')->with('typecla', $typecla)->with('serie', $serie)->with('promo', $promo)->with('typeenseigne', $typeenseigne)->with('typeclah', $typeclah);
  }
  public function modifieclasse(Request $request, $CODECLAS){
    $modifycla = Classes::find($CODECLAS);
    if ($modifycla) {
      $modifycla->LIBELCLAS = $request->input('libclasse');
      $modifycla->TypeCours = $request->input('typecours');
      $modifycla->TYPECLASSE = $request->input('typclasse');
      $modifycla->CYCLE = $request->input('cycle');
      $modifycla->SERIE = $request->input('typeserie');
      $modifycla->CODEPROMO = $request->input('typepromo');
      $modifycla->Niveau = $request->input('numero');
      $modifycla->TYPEENSEIG = $request->input('typeensei');
      $modifycla->update();
      return back()->with('status','Modifier avec succes');
    }
    else {
      return back()->withErrors('Erreur lors de la modification.');

    }
  }

  public function pagedetail($MATRICULE)
{
    // Récupérer les informations de l'élève
    $eleve = Eleve::where('MATRICULE', $MATRICULE)->first();
    
    // Si l'élève n'est pas trouvé, rediriger ou gérer l'erreur
    if (!$eleve) {
        return redirect()->back()->with('error', 'Élève non trouvé');
    }

    // Récupérer les données associées
    $allClass = Classes::all();
    $serie = Serie::get();
    $promotion = Promo::all();
    $typeenseigne = Typeenseigne::get();
    $typeclah = Typeclasse::get();
    $libelles = Params2::first();
    $echeancee = Echeance::where('MATRICULE', $eleve->MATRICULE)->get();
    $scolarite = Scolarite::where('MATRICULE', $eleve->MATRICULE)->get();      

    // Calcul des sommes pour les différentes catégories de frais
    $sums = Scolarite::where('MATRICULE', $MATRICULE)
        ->whereIn('AUTREF', [1, 2, 3, 4, 5, 6])
        ->get()
        ->groupBy('AUTREF')
        ->map(function ($group) {
            return $group->sum('MONTANT');
        });

    // Assigner les sommes ou définir à 0 si aucun résultat
    $sommeScolarité = $sums->get(1, 0);
    $sommeArriéré = $sums->get(2, 0);
    $sommeFrais1 = $sums->get(3, 0);
    $sommeFrais2 = $sums->get(4, 0);
    $sommeFrais3 = $sums->get(5, 0);
    $sommeFrais4 = $sums->get(6, 0);

    // Récupération de la valeur typeclasse pour l'élève
    $typeclasse = $eleve->typeclasse;

    // Calcul de la somme totale en fonction de typeclasse
    if ($typeclasse == 1) {
        $sommeTotale = Scolarite::where('MATRICULE', $MATRICULE)
            ->whereIn('AUTREF', [1, 2])
            ->sum('MONTANT');
    } else {
        $sommeTotale = Scolarite::where('MATRICULE', $MATRICULE)
            ->whereIn('AUTREF', [1, 2, 3, 4, 5, 6])
            ->sum('MONTANT');
    }

    // Retourner la vue avec les données compactées
    return view('pages.inscriptions.pagedetail', compact(
        'sommeScolarité', 'sommeArriéré', 'sommeFrais1', 
        'sommeFrais2', 'sommeFrais3', 'sommeFrais4', 
        'eleve', 'allClass', 'serie', 'promotion', 
        'typeclah', 'typeenseigne', 'libelles', 'echeancee',
        'sommeTotale'
    ));
}

  
  

}