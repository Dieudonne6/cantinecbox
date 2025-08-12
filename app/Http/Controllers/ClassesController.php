<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Eleve;
use App\Models\Classes;
use App\Models\Contrat;
use App\Models\Paramcontrat;
use App\Models\Duplicatafacture;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inscriptioncontrat;
use App\Models\Paiementglobalcontrat;
use App\Models\Paiementcontrat;
use App\Models\Moiscontrat;
use App\Models\Facturenormalise;
use App\Models\Usercontrat;
use App\Models\User;
use App\Models\Paramsfacture;
use App\Models\Facturenormaliseinscription;
use App\Models\Qrcode;
use App\Models\Params2;
use GuzzleHttp\Client;
use App\Http\Requests\InscriptionCantineRequest;
use App\Http\Requests\PaiementCantineRequest;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

// use Barryvdh\DomPDF\PDF;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use DateTime;
use Carbon\Carbon;

use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

// /momo/

// use Endroid\QrCode\Response\QrCodeResponse;
// use Endroid\QrCode\Writer\PngWriter;
// use Endroid\QrCode\Encoding\Encoding;
// use Endroid\QrCode\writeFile\writeFile;
// use Endroid\QrCode\ErrorCorrectionLevel;
// use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\Writer\FileWriter;


class ClassesController extends Controller
{
    public function classe(Request $request){
        if(Session::has('account')){
            $elev = Eleve::get();

        // Récupérer les matricules des élèves dont le statut de contrat est égal à 1
        $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

        // Récupérer les noms et prénoms des élèves correspondants
        $eleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
            ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
            ->orderBy('NOM', 'asc')
            ->get();
            // dd($eleves);

            // Les noms des classes à exclure
            // $classesAExclure = ['NON', 'DELETE'];

            // Récupérer toutes les classes sauf celles à exclure
            $classes = Classes::where('TYPECLASSE', 1)->get();
            // $classes = Classes::get();
            $fraiscontrat = Paramcontrat::first(); 
            Session::put('eleves', $eleves);
            Session::put('classes', $classes);
            Session::put('fraiscontrats', $fraiscontrat);
            // Convertir une chaîne de latin1 à UTF-8

            return view('pages.classes')->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat)->with('elev', $elev);
        } 
        return redirect('/');
    }

    // page liste de tout les contrat disponible 

    public function listecontrat() 
    {
            $elev = Eleve::get();
            $pramcontrat = Paramcontrat::first();
        // Récupérer les matricules des élèves dont le statut de contrat est égal à 1
        $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

        // Récupérer les noms et prénoms des élèves correspondants
        $eleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
            ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
            ->orderBy('NOM', 'asc')
            ->get();
            // dd($eleves);

            // Les noms des classes à exclure
            // $classesAExclure = ['NON', 'DELETE'];

            // Récupérer toutes les classes sauf celles à exclure
            $classes = Classes::where('TYPECLASSE', 1)->get();
            // $classes = Classes::get();
            $fraiscontrat = Paramcontrat::first(); 
            Session::put('eleves', $eleves);
            Session::put('classes', $classes);
            Session::put('fraiscontrats', $fraiscontrat);
            // Convertir une chaîne de latin1 à UTF-8

            // -------------------------------------------------

                    // // Liste des mots à exclure
                    // $excludedWords = ["DELETE", 'NON'];
                        
                    // // Construire la requête initiale
                    // $query = Classes::query();

                    // // Ajouter des conditions pour exclure les mots
                    // foreach ($excludedWords as $word) {
                    //     $query->where('CODECLAS', 'not like', '%' . $word . '%');
                    // }

                    // // Récupérer les résultats
                    // $class = $query->get();
                   

            // -------------------------------------------------

            $montantMensuelDefaut = $pramcontrat->coutmensuel_paramcontrat;
             // Montants fixes pour certains mois
             $montantsFixes = [
                'Septembre' => $montantMensuelDefaut,
                'Decembre' => $montantMensuelDefaut,
                'Avril' => $montantMensuelDefaut,
            ];


            // // Récupérer les mois d'inscription du contrat de l'élève
            // $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)
            //     ->pluck('id_moiscontrat')
            //     ->toArray();

            // Récupérer tous les mois disponibles et les limiter aux 12 premiers mois
            $allmoiscontrat = Moiscontrat::take(12)
                ->pluck('nom_moiscontrat', 'id_moiscontrat')
                ->toArray();

            // Définir l'ordre des mois de septembre à août
            $order = [
                'Septembre', 'Octobre', 'Novembre', 'Decembre',
                'Janvier', 'Fevrier', 'Mars', 'Avril',
                'Mai', 'Juin'
            ];

            // Filtrer les mois qui ne sont pas dans les contrats d'inscription
            $moisNonPayes = $allmoiscontrat;

            $moisAvecMontants = [];
            foreach ($moisNonPayes as $id => $nom) {
                // Utiliser le montant fixe si le mois est dans $montantsFixes, sinon le montant par défaut
                // $montantTotal = $montantsFixes[$nom] ?? $montantMensuelDefaut;
                // $montantPaye = $montantsPayes[$nom] ?? 0; // Si aucun paiement n'a été fait, utiliser 0
                // $montantRestant = $montantTotal - $montantPaye;


                // Ajouter les informations du mois et le montant restant
                $moisAvecMontants[$id] = [
                    'nom' => $nom,
                    // 'montant_restant' => $montantRestant
                ];
            }

             // Réorganiser les mois selon l'ordre défini
            $moisCorrespondants = [];
            foreach ($order as $mois) {
                 foreach ($moisAvecMontants as $id => $info) {
                     if ($info['nom'] === $mois) {
                         $moisCorrespondants[$id] = $info['nom'];
                         break;
                     }
                 }
             }


            // Réorganiser les mois selon l'ordre défini
            // $moisCorrespondants = $moisNonPayes;

            
            $fraismensuelle = $pramcontrat->coutmensuel_paramcontrat;
            // $fraismensuelle = 0;
            // dd($moisCorrespondants);
            // ->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants)
            return view('pages.listecontrat')->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat)->with('elev', $elev)->with('moisCorrespondants', $moisCorrespondants)->with('fraismensuelle', $fraismensuelle);
        
        return redirect('/');
    }
    

    public function filterEleve($CODECLAS){
        $eleves = Eleve::orderBy('NOM', 'asc')->get();
        // Les noms des classes à exclure
        // $classesAExclure = ['NON', 'DELETE'];

        // Récupérer toutes les classes sauf celles à exclure
        $classes = Classes::where('TYPECLASSE', 1)->get();
        // $classes = Classes::get();
        $fraiscontrat = Paramcontrat::first(); 

        // Récupérer les matricules des élèves dont le statut de contrat est égal à 1
        $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

        // Récupérer les noms et prénoms des élèves correspondants
        $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
            ->where('CODECLAS', $CODECLAS)
            ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
            ->orderBy('NOM', 'asc')
            ->get();

        // $filterEleves = Eleve::where('CODECLAS', $CODECLAS)
        // ->orderBy('NOM', 'asc')
        // ->get();
        Session::put('fraiscontrats', $fraiscontrat);
        Session::put('fill', $filterEleves);
        
        // dd($fill);
        return view('pages.filterEleve')->with("filterEleve", $filterEleves)->with('classe', $classes)->with('eleve', $eleves)->with('fraiscontrats', $fraiscontrat);
    }
    
  
    
    

    public function paiementcontrat(Request $request, $CODECLAS, $MATRICULE){

        // $fill = Session::get('fill');

        // dd($CODECLAS);

            

            // dd($moisCorrespondants);

         // Afficher les mois correspondants à chaque ID du tableau $difference
            // foreach ($moisCorrespondants as $id_moiscontrat => $nom_moiscontrat) {
            //     echo"<br> <br> <br> <br>";
            //     echo "ID Moiscontrat : $id_moiscontrat, Mois : $nom_moiscontrat <br>";
            // }


            // $allmoiscontratarray = $allmoiscontrat->toArray();
            // dd($allmoiscontratarray);


            // $moisContratArray = $inscriptioncontrats->toArray();
            // dd($moisContratArray);
    
                // $moiscontrat = $inscriptioncontrat->id_moiscontrat;
                // dd($inscriptioncontrat->id_moiscontrat) ;
                // dd($moiscontrat);


            // $moiscontrat = $inscriptioncontrat->id_moiscontrat;
            // dd($idcontrateleve);

            Session::put('matricule', $MATRICULE);

            $contrat = Contrat::where('eleve_contrat', $MATRICULE)->first();
            $eleveCon = Eleve::where('MATRICULE', $MATRICULE)->first();
            $nomCompletEleveCon = $eleveCon->NOM .' '. $eleveCon->PRENOM;
            $classeEleve = Classes::where('CODECLAS', $CODECLAS)->first();
           

            if ($contrat){

                // echo("oui L'eleve existe dans la table contrat !!!!!!!!!! ");
                $idcontrateleve = $contrat->id_contrat;
                Session::put('idcontratEleve', $idcontrateleve);


                // $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)->get(['id_moiscontrat']);
                // $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)->pluck('id_moiscontrat')->toArray();
                // $allmoiscontrat = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->take(12)->toArray();
                // // dd($allmoiscontrat);

                // $difference = array_diff(array_keys($allmoiscontrat), $inscriptioncontrats);

                // // dd($difference);
                // $moisCorrespondants = [];
                // foreach ($difference as $id_moiscontrat) {
                //     if (array_key_exists($id_moiscontrat, $allmoiscontrat)) {
                //         $moisCorrespondants[$id_moiscontrat] = $allmoiscontrat[$id_moiscontrat];
                //     }
                // }
                


                // Récupérer les paramètres du contrat
                $pramcontrat = Paramcontrat::first();

                // Déterminer le montant mensuel par défaut en fonction du type d'enseignement
                if ($classeEleve->TYPEENSEIG == 1) {
                    $montantMensuelDefaut = $pramcontrat->fraisinscription2_paramcontrat;
                } else {
                    $montantMensuelDefaut = $pramcontrat->coutmensuel_paramcontrat;
                }

                // Montants fixes pour certains mois
                $montantsFixes = [
                    'Septembre' => $montantMensuelDefaut,
                    'Decembre' => $montantMensuelDefaut,
                    'Avril' => $montantMensuelDefaut,
                ];


                    // // Récupérer les mois d'inscription du contrat de l'élève
                    $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)
                        ->pluck('id_moiscontrat')
                        ->toArray();

                    // Récupérer tous les mois disponibles et les limiter aux 12 premiers mois
                    $allmoiscontrat = Moiscontrat::take(12)
                        ->pluck('nom_moiscontrat', 'id_moiscontrat')
                        ->toArray();

                    // Définir l'ordre des mois de septembre à août
                    $order = [
                        'Septembre', 'Octobre', 'Novembre', 'Decembre',
                        'Janvier', 'Fevrier', 'Mars', 'Avril',
                        'Mai', 'Juin'
                    ];

                // Filtrer les mois qui ne sont pas dans les contrats d'inscription
                $moisNonPayes = array_diff_key($allmoiscontrat, array_flip($inscriptioncontrats));

                   // Récupérer les montants payés par l'élève pour chaque mois depuis Facturenormalise
                    // Récupérez d'abord tous les paiements
                   // 1) On agrège les montants payés par mois (nom de mois) :
                    $paiements = Facturenormalise::where('idcontrat', $idcontrateleve)
                    ->where('statut', 1)
                    ->get(['moispayes', 'montant_par_mois']);

                $montantsPayes = [];
                foreach ($paiements as $paiement) {
                $moisList = explode(',', $paiement->moispayes);
                $nb = count($moisList);
                foreach ($moisList as $moisName) {
                    $moisName = trim($moisName);
                    // On répartit le montant si tu veux, sinon tu fais un cumul brut :
                    // $partie = $paiement->montant_par_mois / $nb;
                    // $montantsPayes[$moisName] = ($montantsPayes[$moisName] ?? 0) + $partie;

                    // Ici on fait juste un cumul entier :
                    $montantsPayes[$moisName] = ($montantsPayes[$moisName] ?? 0)
                                            + $paiement->montant_par_mois;
                }
                }

                // 2) Boucle pour calculer baseline et restant
                    $moisAvecMontants = [];
                    foreach ($moisNonPayes as $id => $nom) {
                    // montant « par défaut » (fixe ou mensuel)
                    $montantDefaut = $montantsFixes[$nom] ?? $montantMensuelDefaut;

                    // paiement déjà fait sur ce mois (0 si aucun)
                    $paye = $montantsPayes[$nom] ?? 0;

                    // baseline = payé (s’il y en a), sinon montant par défaut
                    $baseline = $paye > 0 ? $paye : $montantDefaut;

                    // restant = montant par défaut – payé
                    $restant = $montantDefaut - $paye;

                        $moisAvecMontants[$id] = [
                            'nom'             => $nom,
                            'baseline'        => $baseline,
                            'montant_restant' => $restant,
                        ];
                        }

                        // 3) Si tu veux afficher « (Réf : X – Reste : Y) »
                        $moisCorrespondants = [];
                        foreach ($order as $mois) {
                        foreach ($moisAvecMontants as $id => $info) {
                            if ($info['nom'] === $mois) {
                                $moisCorrespondants[$id] = sprintf(
                                    '%s (Reste : %d)',
                                    $info['nom'],
                                    // $info['baseline'],
                                    $info['montant_restant']
                                );
                                break;
                            }
                        }
            }

        // dd($moisCorrespondants);


                // dd($moisCorrespondants);
                // Réorganiser les mois selon l'ordre défini
                // $moisCorrespondants = [];
                // foreach ($order as $mois) {
                //     foreach ($moisNonPayes as $id => $nom) {
                //         if ($nom === $mois) {
                //             $moisCorrespondants[$id] = $nom;
                //             break;
                //         }
                //     }
                // }

                // dd($moisCorrespondants);


                if (count($moisCorrespondants) <= 0){
                    return redirect()->back()->with('status', 'L\'eleve est a jour');
                    // dd('moisCorrespondants > 0');
                }else{
                    // if (($CODECLAS === "MAT1") || ($CODECLAS === "MAT2")  || ($CODECLAS === "MAT2II")  || ($CODECLAS === "MAT3")  || ($CODECLAS === "MAT3II")  || ($CODECLAS === "PREMATER")) {
                    if ($classeEleve->TYPEENSEIG == 1) {
                        // echo("oui l'eleve est de la maternelle");

                        $pramcontrat = Paramcontrat::first();
                        $fraismensuelle = $pramcontrat->fraisinscription2_paramcontrat;
                        // dd($fraismensuelle);

                        if($inscriptioncontrats){
                            // echo("le contrat existe dans la table inscriptioncontrat");
                            // dd($inscriptioncontrat);
                            // dd($moiscontrat);
                            // dd($moisCorrespondants);
                            return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants)->with('nomCompletEleveCon', $nomCompletEleveCon);

                        }
                        else {
                            // dd($moisCorrespondants);

                            // $moisCorrespondants = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();

                            // echo("le contrat n'existe pas dans la table inscriptioncontrat");
                            
                            return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants)->with('nomCompletEleveCon', $nomCompletEleveCon);

                        }


                    }else{

                        // echo("non l'eleve n'est pas de la maternelle");

                        $pramcontrat = Paramcontrat::first();
                        $fraismensuelle = $pramcontrat->coutmensuel_paramcontrat;
                        // dd($fraismensuelle);

                        if($inscriptioncontrats){
                            // dd($moisCorrespondants);

                            // echo("le contrat existe dans la table inscriptioncontrat");
                            // dd($inscriptioncontrat);
                            // dd($moiscontrat);
                            
                            return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants)->with('nomCompletEleveCon', $nomCompletEleveCon);

                        }
                        else {
                            // dd($moisCorrespondants);

                            // $moisCorrespondants = Moiscontrat::pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();

                            return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle)->with('moisCorrespondants', $moisCorrespondants)->with('nomCompletEleveCon', $nomCompletEleveCon);

                        }

                        


                        // return view('pages.paiementcontrat')->with('fraismensuelle', $fraismensuelle);

                    }
                }
            

            } else {
                // return view('pages.inscriptioncontrat');
                return back()->with('status','Le contrat n\'existe pas.Veuillez créer un contrat pour l\'eleve');

            }


}


    
        
public function savepaiementcontrat(PaiementCantineRequest $request) {

                $idcontratEleve = Session::get('idcontratEleve');
                $moisCoches = $request->input('moiscontrat');
                $montantmoiscontrat = $request->input('montantcontrat');
                $montanttotal = $request->input('montanttotal');
                $datepaiementcontrat = $request->input('date');
                $montantParMoisReel = $request->input('montantcontratReel');
                $montantParMoisReelInt = intval($montantParMoisReel);
                $id_usercontrat = Session::get('id_usercontrat');
                // dd($datepaiementcontrat);
                // $id_usercontrat = $request->input('id_usercontrat');

                // dd($montanttotal);
                $anneeActuelle = date('Y');

                // generer une valeur aleatoire comprise entre 10000000 et 99999999 et verifier si elle existe deja dans la table.
                // Si la valeur est déjà présente, exists() renverra true, et la boucle continuera à s'exécuter pour générer une nouvelle valeur.
                // Si la valeur n'est pas présente (c'est-à-dire qu'elle est unique), la condition exists() renverra false, et la boucle s'arrêtera.

                // do {
                //      // Génère un nombre aléatoire entre 10000000 et 99999999
                //     $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
                // } while (DB::table('paiementcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());
                
                // recuperer le debut de l'anne scolaire en cours et forme l'annee scolaire en cours

                $infoParamContrat = Paramcontrat::first();
                $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
                $anneeSuivante = $debutAnneeEnCours + 1;
                $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;
                // dd($annneScolaireEnCours);

                // Trouver la dernière référence pour l'année scolaire en cours
                // $derniereReference = DB::table('paiementcontrat')
                // ->where('reference_paiementcontrat', 'like', '%/'.$anneeScolaireEnCours)
                // ->orderBy('id_paiementcontrat', 'desc')
                // ->value('reference_paiementcontrat');

                // // Extraire et incrémenter la partie numérique
                // if ($derniereReference) {
                // $numeroActuel = (int) explode('/', $derniereReference)[0];
                // $nouveauNumero = $numeroActuel + 1;
                // } else {
                // $nouveauNumero = 1; // Commencer à 1 si aucune référence n'existe pour cette année
                // }

                // // Générer la nouvelle référence
                // $nouvelleReference = str_pad($nouveauNumero, 7, '0', STR_PAD_LEFT) . '/' . $anneeScolaireEnCours;

                // dd($nouvelleReference);




                // recuperer les nom des mois cochee

                // Array des noms des mois
                $nomsMoisCoches = [];
                if (is_array($moisCoches)) {

                    // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                    foreach ($moisCoches as $id_moiscontrat) {
                        // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                        $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                        
                        // Vérifiez si le mois existe
                        if ($mois) {
                            // Ajouter le nom du mois à l'array
                            $nomsMoisCoches[] = $mois->nom_moiscontrat;
                        }
                    }
                }
                // Convertir le tableau en une chaîne de caractères
                $moisConcatenes = implode(',', $nomsMoisCoches);
                // dd($moisConcatenes);
                // Récupérer la somme des montants de paiement précédents
                // $soldeavant_paiementcontrat = DB::table('paiementglobalcontrat')
                // ->where('id_contrat', $idcontratEleve)
                // ->sum('montant_paiementcontrat');

                // dd($soldeavant_paiementcontrat);
                // Calculer le solde après le paiement en ajoutant le montant du paiement actuel à la somme des montants précédents
                // $soldeapres_paiementcontrat = $soldeavant_paiementcontrat + $montanttotal;
                // dd($soldeapres_paiementcontrat);




                



        // echo('paiement effectuer avec succes');



        // GESTION DE LA FACTURE NORMALISE

    $matriculeeleve = Session::get('matricule');
    $nomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('NOM');
    $prenomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('PRENOM');
    $classeeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('CODECLAS');
    $nomcompleteleve = $nomeleve .' '. $prenomeleve;
 

    $parametrefacture = Params2::first();
    $ifuentreprise = $parametrefacture->ifu;
    $tokenentreprise = $parametrefacture->token;
    $taxe = $parametrefacture->taxe;
    $type = $parametrefacture->typefacture;

    $parametreetab = Params2::first();
    // $nometab = $parametreetab->NOMETAB;
    // $villeetab = $parametreetab->VILLE;

            $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';


    // dd($classeeleve);

    // $infocontrateleve = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
    // ->orderBy('id_paiementcontrat', 'desc')->first();

    // $toutmoiscontrat = $infocontrateleve->mois_paiementcontrat;
    $moisavecvirg = implode(',', $nomsMoisCoches);
    $toutmoiscontrat = $moisavecvirg;


    // dd($nomsMoisCoches);


    // $invoiceItems = 
    //      [
    //         [
    //                 // 'date' => $infocontrateleve->date_paiementcontrat,
    //                 // 'montantpaiement' => intval($infocontrateleve->montant_paiementcontrat), // Convertir le prix en entier
    //                 // 'mois' => $infocontrateleve->mois_paiementcontrat,
    //                 // 'eleve' => $nomcompleteleve,
    //                 // 'classe' => $classeeleve,
    //                 // 'taxGroup' => 'B', // La taxe reste la même, adaptez si nécessaire

    //                 'name' => 'contrat de cantine',
    //                 // 'price' => intval($infocontrateleve->montant_paiementcontrat),
    //                 'price' => intval($montanttotal), 
    //                 'quantity' => 1,
    //                 'taxGroup' => $taxe, // La taxe reste la même, adaptez si nécessaire
    //         ]
    //             ];
        
        
            
    //         $invoiceRequestDto = [
    //             "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
    //             "type" => $type,
    //             "items" => $invoiceItems,
    //             "operator" => [
    //                 // "name" => $nomecole
    //                 "name" => "test"
    //             ]
    //         ];
    
    //         // dd($invoiceRequestDto);
    
    //         $jsonData = json_encode($invoiceRequestDto, JSON_UNESCAPED_UNICODE);

        
                    // -------------------------------
                        //  CREATION DE LA FACTURE
                    // -------------------------------
                    $items = []; // Initialiser un tableau vide pour les éléments
                    // $facturenormalise->idcontrat = $idcontratEleve;
                    // $facturenormalise->moispayes = $moisConcatenes;

                    // dd($nomsMoisCoches);
                  
                    foreach ($nomsMoisCoches as $idmois => $mois) {
                        $items[] = [
                            'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                            'price' => intval($montantmoiscontrat),
                            'quantity' => 1,
                            'taxGroup' => $taxe,
                        ];

                        // if ($mois == 'Decembre') {
                        //     $infoFacture = Facturenormalise::where('idcontrat', $idcontratEleve)
                        //     ->where('moispayes', 'Decembre')
                        //     ->get();  
                        //     $totalMontantinfoFacture = $infoFacture->sum('montant_par_mois');
                        //     $sommeDesMontant = $totalMontantinfoFacture + $montantmoiscontrat;

                        //     // dd($totalMontantinfoFacture);
                        //     $montantAPayer = 10000;
                        //     if ($sommeDesMontant < $montantAPayer) {
                        //         $saveMois = 1;
                        //     }else {
                        //         $saveMois = 0;
                        //     }
                        // }


                        // Définir $montantAPayer par défaut pour tous les mois
                        if (in_array($mois, ['Decembre', 'Septembre', 'Avril'])) {
                            // Montant spécifique pour certains mois
                            switch ($mois) {
                                case 'Decembre':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                                case 'Septembre':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                                case 'Avril':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                            }
                        } else {
                            // Montant par défaut pour les autres mois
                            $montantAPayer = $montantParMoisReelInt;
                        }
                        
                        // Requête pour récupérer les informations de facture
                        $infoFacture = Facturenormalise::where('idcontrat', $idcontratEleve)
                            ->where('moispayes', $mois)
                            ->get();
                        // dd($mois);
                        // Calculer le total des montants
                        $totalMontantinfoFacture = $infoFacture->sum('montant_par_mois');

                        // Si $totalMontantinfoFacture est null, le remplacer par 0
                        $totalMontantinfoFacture = $totalMontantinfoFacture ?? 0;

                        // Calculer la somme des montants
                        $sommeDesMontant = $totalMontantinfoFacture + $montantmoiscontrat;
                        // dd($montantAPayer);

                        // Déterminer si le mois peut être sauvegardé
                        if ($sommeDesMontant < $montantAPayer) {
                            $saveMois = 1;                      
                        } else {
                            $saveMois = 0;
                        }
                        
                        // $saveMois = $sommeDesMontant < $montantAPayer ? 1 : 0;
                        // $saveMois = $montantRestant <= 0 ? 1 : 0;

                        // dd($saveMois);


                    }
                    // dd($items);
            // Préparez les données JSON pour l'API
            $jsonData = json_encode([
                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                // "aib" => "A",
                "type" => $type,
                "items" => $items,
                // "items" => [
                //         [
                //             'name' => 'Frais cantine pour :'.$$mois,
                //             // 'price' => intval($infocontrateleve->montant_paiementcontrat),
                //             'price' => intval($montantmoiscontrat), 
                //             'quantity' => 1,
                //             'taxGroup' => $taxe,
                //         ]                    
                   
                // ],
                "client" => [
                    // "ifu" => '',
                    "name"=>  $nomcompleteleve,
                    // "contact" => "string",
                    // "address"=> "string"
                ],
                "operator" => [
                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                ],
                "payment" => [
                    [
                    "name" => "ESPECES",
                      "amount" => intval($montanttotal)
                    ]
                  ],
            ]);
            // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


            // dd($jsonData);
    
            // Définissez l'URL de l'API de facturation
            $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
            
    
            // Définissez le jeton d'authentification

                $token = $tokenentreprise;
            //    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjEyMDEyMDE1NzQ1MDJ8VFMwMTAxMjE5OCIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyOTAwMTYwNiwiZXhwIjoxOTI0OTAyMDAwLCJpYXQiOjE3MjkwMDE2MDYsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.t0VBBtOig972FWCW2aFk7jyb-SHKv1iSZ9bkM-IGc2M";
                // $token = $tokenentreprise;
    
            // Effectuez la requête POST à l'API
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
            // Exécutez la requête cURL et récupérez la réponse
    $response = curl_exec($ch);
    // dd($response);
    
    // Vérifiez les erreurs de cURL
    if (curl_errno($ch)) {
        // echo 'Erreur cURL : ' . curl_error($ch);
        return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
    }
    
    // Fermez la session cURL
    curl_close($ch);
    
    // Affichez la réponse de l'API
    $decodedResponse = json_decode($response, true);
    // dd($decodedResponse);
    
    // Vérifiez si l'UID est présent dans la réponse
    if (isset($decodedResponse['uid'])) {
        // L'UID de la demande
        $uid = $decodedResponse['uid'];
        // $taxb = 0.18;
    
        // Affichez l'UID
        // echo "L'UID de la demande est : $uid";

        
                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

            // Définissez l'URL de l'API de confirmation de facture
            $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
            // Configuration de la requête cURL pour la confirmation
            $chRecuperation = curl_init($recuperationUrl);
            curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Length: 0'
            ]);
            curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

            // Exécutez la requête cURL pour la confirmation
            $responseRecuperation = curl_exec($chRecuperation);
            // dd($responseRecuperation);
            // Vérifiez les erreurs de cURL pour la confirmation


            // Fermez la session cURL pour la confirmation
            curl_close($chRecuperation);

        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedDonneFacture = json_decode($responseRecuperation, true);
        // dd($decodedDonneFacture);

        $facturedetaille = json_decode($jsonData, true);
        $ifuEcoleFacture = $decodedDonneFacture['ifu'];
        $itemFacture = $decodedDonneFacture['items'];
        $jsonItem = json_encode($itemFacture);
        $doneeDetailleItemFacture = $itemFacture['0'];
        $nameItemFacture = $doneeDetailleItemFacture['name'];
        $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
        $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
        $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
        // $idd = $responseRecuperation.ifu;
        $nameClient = $decodedDonneFacture['client']['name'];
        // dd($prixTotalItemFacture);

    
                // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

        // ACTION pour la confirmation
        $actionConfirmation = 'confirm';
    
        // Définissez l'URL de l'API de confirmation de facture
        $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
    
        // Configuration de la requête cURL pour la confirmation
        $chConfirmation = curl_init($confirmationUrl);
        curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Length: 0'
        ]);
        curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
        // Exécutez la requête cURL pour la confirmation
        $responseConfirmation = curl_exec($chConfirmation);
    
        // Vérifiez les erreurs de cURL pour la confirmation
        if (curl_errno($chConfirmation)) {
            // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
            return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

        }
    
        // Fermez la session cURL pour la confirmation
        curl_close($chConfirmation);
        
        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedResponseConfirmation = json_decode($responseConfirmation, true);
        // dd($decodedResponseConfirmation);
        
    
        $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

        $counters = $decodedResponseConfirmation['counters'];

        $nim = $decodedResponseConfirmation['nim'];

        $dateTime = $decodedResponseConfirmation['dateTime'];

        // Générer le code QR
        $qrCodeString = $decodedResponseConfirmation['qrCode'];

        $reffactures = $nim.'-'.$counters;

        $reffacture = explode('/', $reffactures)[0];

                // gestion du code qr sous forme d'image

                $fileNameqrcode = $nomcompleteleve . time() . '.png';
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrCodeString)
                    ->size(100)
                    // ->margin(10)
                    ->build();

                    $qrcodecontent = $result->getString();




                    // $paiementglobalcontrat =  new Paiementglobalcontrat();
                    
                    // $paiementglobalcontrat->soldeavant_paiementcontrat = $montanttotal;
                    // $paiementglobalcontrat->montant_paiementcontrat = $montanttotal;
                    // $paiementglobalcontrat->soldeapres_paiementcontrat = 0;
                    // $paiementglobalcontrat->id_contrat = $idcontratEleve;
                    // $paiementglobalcontrat->id_usercontrat = $id_usercontrat;
                    // $paiementglobalcontrat->date_paiementcontrat = $datepaiementcontrat;
                    // //     $paiementglobalcontrat->id_usercontrat = null;
                    // $paiementglobalcontrat->anne_paiementcontrat = $anneeActuelle;
                    // $paiementglobalcontrat->reference_paiementcontrat = $valeurDynamiqueNumerique;
                    // $paiementglobalcontrat->statut_paiementcontrat = 1;
                    // //     $paiementglobalcontrat->datesuppr_paiementcontrat = null;
                    // //    $paiementglobalcontrat->idsuppr_usercontrat = null;
                    // //    $paiementglobalcontrat->motifsuppr_paiementcontrat = null;
                    // $paiementglobalcontrat->mois_paiementcontrat = $moisConcatenes;
     
                    // $paiementglobalcontrat->save();
     
     
     
                    // Récupérer l'id_paiementcontrat de la table paiementglobalcontrat qui correspond a l'id du contrat
                    //  $idPaiementContrat = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
                    //  ->orderBy('id_paiementcontrat', 'desc')
                    //  ->value('id_paiementcontrat');
                     // dd($idPaiementContrat);                
     
                     // ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT
     
                     // dd($soldeavant_paiementcontrat);
     
                    
                     
                     
                        // ********************************
                     
                     // generer une valeur aleatoire comprise entre 10000000 et 99999999 et verifier si elle existe deja dans la table.
                    // Si la valeur est déjà présente, exists() renverra true, et la boucle continuera à s'exécuter pour générer une nouvelle valeur.
                    // Si la valeur n'est pas présente (c'est-à-dire qu'elle est unique), la condition exists() renverra false, et la boucle s'arrêtera.
                    do {
                        // Génère un nombre aléatoire entre 10000000 et 99999999
                    $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
                    } while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());

                    // dd($moisCoches);  

                    // ENREGISTREMENT DANS LA TABLE INSCRIPTIONCONTRAT
                     // Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
                     foreach ($moisCoches as $id_moiscontrat) {
                        if ($saveMois == 0) {
                            Inscriptioncontrat::create([
                                 // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                                 'id_contrat' => $idcontratEleve,
                                 'id_moiscontrat' => $id_moiscontrat,
                                 'anne_inscription' => $debutAnneeEnCours,
                                
                             ]);
                        }else{
                            // 
                        }
                     }

                     // recuperer les nom des mois cochee

                    // Array des noms des mois
                    $nomsMoisCoches = [];

                    // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                    foreach ($moisCoches as $id_moiscontrat) {
                        // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                        $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                        
                        // Vérifiez si le mois existe
                        if ($mois) {
                            // Ajouter le nom du mois à l'array
                            $nomsMoisCoches[] = $mois->nom_moiscontrat;
                        }
                    }

                    // Convertir le tableau en une chaîne de caractères
                    $moisConcatenes = implode(',', $nomsMoisCoches);
                    // dd($moisConcatenes);
                    // Récupérer la somme des montants de paiement précédents
                    $soldeavant_paiementcontrat = DB::table('paiementglobalcontrat')
                    ->where('id_contrat', $idcontratEleve)
                    ->sum('montant_paiementcontrat');


                    $InfoUtilisateurConnecter =  User::where('id', $id_usercontrat)->first();
                    $idUserCont =  $InfoUtilisateurConnecter->id;
                    $idUserContInt = intval($idUserCont);

                    // dd($soldeavant_paiementcontrat);
                    // Calculer le solde après le paiement en ajoutant le montant du paiement actuel à la somme des montants précédents
                    $soldeapres_paiementcontrat = $soldeavant_paiementcontrat + $montantmoiscontrat;
                    // dd($soldeapres_paiementcontrat);

                    // ENREGISTREMENT DANS LA TABLE PAIEMENTGLOBALCONTRAT
                    $paiementglobalcontrat =  new Paiementglobalcontrat();
                        
                    $paiementglobalcontrat->soldeavant_paiementcontrat = $soldeavant_paiementcontrat;
                    $paiementglobalcontrat->montant_paiementcontrat = $montanttotal;
                    $paiementglobalcontrat->soldeapres_paiementcontrat = $soldeapres_paiementcontrat;
                    $paiementglobalcontrat->id_contrat = $idcontratEleve;
                    $paiementglobalcontrat->date_paiementcontrat = $datepaiementcontrat;
                        $paiementglobalcontrat->id_usercontrat = $idUserContInt;
                    $paiementglobalcontrat->anne_paiementcontrat = $debutAnneeEnCours;
                    $paiementglobalcontrat->reference_paiementcontrat = $valeurDynamiqueNumerique;
                    $paiementglobalcontrat->statut_paiementcontrat = 1;
                    //     $paiementglobalcontrat->datesuppr_paiementcontrat = null;
                    //    $paiementglobalcontrat->idsuppr_usercontrat = null;
                    //    $paiementglobalcontrat->motifsuppr_paiementcontrat = null;
                    $paiementglobalcontrat->mois_paiementcontrat = $moisConcatenes;

                    $paiementglobalcontrat->save();

                    // Récupérer l'id_paiementcontrat de la table paiementglobalcontrat qui correspond a l'id du contrat
                    $idPaiementContrat = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
                    ->orderBy('id_paiementcontrat', 'desc')
                    ->value('id_paiementcontrat');
                    // dd($idPaiementContrat);                

                    // ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT

                    // dd($soldeavant_paiementcontrat);
                    // Créer un objet DateTime à partir de la chaîne de caractères
                    $datezz = new DateTime($datepaiementcontrat);

                    // Formater la date sans l'heure
                    $datezzSansHeure = $datezz->format('Y-m-d');  // Cela donnera "2025-02-18"

                    // Parcourir les mois cochés et insérer chaque id de mois dans la table Paiementcontrat
                    foreach ($moisCoches as $id_moiscontrat) {
                        Paiementcontrat::create([
                            // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                            'soldeavant_paiementcontrat' => $soldeavant_paiementcontrat,
                            'montant_paiementcontrat' => $montantmoiscontrat,
                            'soldeapres_paiementcontrat' => $soldeapres_paiementcontrat,
                            'id_contrat' => $idcontratEleve,
                            'date_paiementcontrat' => $datepaiementcontrat,
                            // 'date_paiementcontrat' => $datezzSansHeure,
                            'id_usercontrat' => $idUserContInt,
                            'mois_paiementcontrat' => $id_moiscontrat,
                            'anne_paiementcontrat' => $debutAnneeEnCours,
                            'reference_paiementcontrat' => $valeurDynamiqueNumerique,
                            'statut_paiementcontrat' => 1,
                            // 'datesuppr_paiementcontrat' => $anneeActuelle,
                            // 'idsuppr_usercontrat' => $anneeActuelle,
                            // 'motifsuppr_paiementcontrat' => $anneeActuelle,
                            'id_paiementglobalcontrat' => $idPaiementContrat,
                            'montanttotal' => $montanttotal
                        ]);
                    }




                    // CALCUL DU TOTALHT ET TOTALTVA

                    $TOTALHT = $montanttotal / 1.18;
                    $totalHTArrondi = 0;
                    $TOTALTVA = 0;

            // ********************************


                    
                    // dd($ifuEcoleFacture);
                    $facturenormalise = new Facturenormalise();
                    $facturenormalise->id = $reffacture;
                    $facturenormalise->codemecef = $codemecef;
                    $facturenormalise->counters = $counters;
                    $facturenormalise->nim = $nim;
                    $facturenormalise->dateHeure = $dateTime;
                    $facturenormalise->ifuEcole = $ifuEcoleFacture;
                    $facturenormalise->MATRICULE = $matriculeeleve;
                    $facturenormalise->idcontrat = $idcontratEleve;
                    $facturenormalise->moispayes = $moisConcatenes;
                    $facturenormalise->classe = $classeeleve;
                    $facturenormalise->nom = $nameClient;
                    $facturenormalise->itemfacture = $jsonItem;
                    $facturenormalise->designation = 'Frais cantine pour: '.$moisConcatenes;
                    $facturenormalise->montant_total = $montanttotal;
                    $facturenormalise->TOTALHT = $totalHTArrondi;
                    $facturenormalise->TOTALTVA = $TOTALTVA;
                    $facturenormalise->montant_par_mois = $montantmoiscontrat;
                    $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
                    $facturenormalise->qrcode = $qrcodecontent;
                    $facturenormalise->statut = 1;
                    $facturenormalise->typefac = 0;
        
                    $facturenormalise->save();



        $paramse = Params2::first(); 

        $logoUrl = $paramse ? $paramse->logoimage: null; 
    
        $NOMETAB = $paramse->NOMETAB;




    // dd($NOMETAB);
            // $id = $fileNameqrcode;
            // $qrcodesin = Qrcode::find($id);
    
            // $qrcodecontent = $qrcodesin->qrcode;
    
        Session::put('factureconfirm', $decodedResponseConfirmation);
        Session::put('fileNameqrcode', $fileNameqrcode);
        Session::put('facturedetaille', $facturedetaille);
        Session::put('reffacture', $reffacture);
        Session::put('classeeleve', $classeeleve);
        Session::put('nomcompleteleve', $nomcompleteleve);
        Session::put('toutmoiscontrat', $toutmoiscontrat);
        Session::put('nameItemFacture', $nameItemFacture);
        Session::put('prixTotalItemFacture', $prixTotalItemFacture);
        Session::put('quantityItemFacture', $quantityItemFacture);
        Session::put('taxGroupItemFacture', $taxGroupItemFacture);
        Session::put('ifuEcoleFacture', $ifuEcoleFacture);
        Session::put('qrCodeString', $qrCodeString);
        Session::put('itemFacture', $itemFacture);
        Session::put('montanttotal', $montanttotal);
        Session::put('totalHTArrondi', $totalHTArrondi);
        Session::put('TOTALTVA', $TOTALTVA);
        Session::put('montantmoiscontrat', $montantmoiscontrat);
        Session::put('qrcodecontent', $qrcodecontent);
        Session::put('NOMETAB', $NOMETAB);
        Session::put('nim', $nim);
        Session::put('datepaiementcontrat', $datepaiementcontrat);
        Session::put('dateTime', $dateTime);
        // Session::put('nometab', $nometab);
        // Session::put('villeetab', $villeetab);



    
        return view('pages.Etats.pdffacture', [
              'factureconfirm' => $decodedResponseConfirmation,
               'fileNameqrcode' => $fileNameqrcode,
               'facturedetaille' => $facturedetaille,
                'reffacture' => $reffacture,
            'ifuEcoleFacture' => $ifuEcoleFacture,
            'nameItemFacture' => $nameItemFacture,
            'prixTotalItemFacture' => $prixTotalItemFacture,
            'quantityItemFacture' => $quantityItemFacture,
            'taxGroupItemFacture' => $taxGroupItemFacture,
               'classeeleve' => $classeeleve,
                'nomcompleteleve' => $nomcompleteleve,
            'toutmoiscontrat' => $toutmoiscontrat,
            'qrCodeString' => $qrCodeString,
            'logoUrl' => $logoUrl,
            'itemFacture' => $itemFacture,
            'montanttotal' => $montanttotal,
            'qrcodecontent' => $qrcodecontent,
            'NOMETAB' => $NOMETAB,
            'nim' => $nim,
            'datepaiementcontrat' => $datepaiementcontrat,
            'dateTime' => $dateTime,
            'totalHTArrondi' => $totalHTArrondi,
            'TOTALTVA' => $TOTALTVA,
            // 'villeetab' => $villeetab,
            // 'qrCodeImage' => $qrCodeImage,
    
                 ]);

                 
    
    
    
    }

    
}



        


        // debut facture normalisee pour tous les paiements de l'annee 2023_2024
        public function genererfacture() {
            // $paiements = DB::table('paiementcontrat')->where('montant_paiementcontrat', '>', 0)->get();
    
            $paiements = DB::table('paiementcontrat')
            ->select('id_contrat', 'mois_paiementcontrat', 'montant_paiementcontrat', 'date_paiementcontrat')
            ->where('montant_paiementcontrat', '>', 0)
            ->where('statut_paiementcontrat', 1)
            ->get()
            ->groupBy('id_contrat')
            ->map(function ($rows) {
                return [
                    'id_contrat' => $rows->first()->id_contrat,
                    'mois' => $rows->pluck('mois_paiementcontrat')->toArray(),
                    'montant_total' => $rows->sum('montant_paiementcontrat'),
                    // 'montant_paiementcontrat' => $rows('montant_paiementcontrat'),
                    // 'date_paiementcontrat' => $rows['date_paiementcontrat'],
                    'details' => $rows->map(function ($row) {
                        return [
                            'mois' => $row->mois_paiementcontrat,
                            'montant_paye' => $row->montant_paiementcontrat,
                            'date_paiementcontrat' => $row->date_paiementcontrat,
                        ];
                    })->toArray(),
                ];
            });
    
    
    
    
            foreach ($paiements as $paiement) {
    
                // dd($paiement['montant_total']);
    
                try {
                    set_time_limit(500); // Augmente le temps d'exécution à 300 secondes (5 minutes)
                    // Appeler la fonction de création de facture normalisée
                    $facture = $this->savepaiementcontrat2($paiement);
    
                    // // Enregistrer la facture dans la base de données
                    // DB::table('factures')->insert([
                    //     'id_paiement' => $paiement->id,
                    //     'facture_data' => json_encode($facture),
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
    
                    Log::info("Facture générée avec succès pour le paiement ID: " );
    
                } catch (\Exception $e) {
                    // Gérer les erreurs de génération de facture
                    Log::error("Erreur lors de la génération de la facture pour le paiement ID: " );
                }
            }
    
             return response()->json(['message' => 'Factures générées avec succès.']);
        }
        
    
    
    
        private function savepaiementcontrat2($paiement){

            $idcontratEleve = $paiement['id_contrat'];
            // $moiscont =  DB::table('paiementcontrat')->where('id_contrat', $idcontratEleve)->pluck('mois_paiementcontrat');
            // dd($moiscont);
    
            $moisCoches = $paiement['mois'];
            
    
            // $nombreElements = $moiscont->count();
            // $montantmoiscontrat = $paiement['montant_total'];
            
            $montanttotal = $paiement['montant_total'];
            $datepaiementcontrat = date('Y-m-d H:i:s');
            $id_usercontrat = Session::get('id_usercontrat');
            // dd($datepaiementcontrat);
            // dd($id_usercontrat);
            $anneeActuelle = date('Y');
    
           
            
    
    
            // recuperer les nom des mois cochee
    
            // Array des noms des mois
            $nomsMoisCoches = [];
            if (is_array($moisCoches)) {
                // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                foreach ($moisCoches as $id_moiscontrat) {
                    // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                    $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
    
                    // Vérifiez si le mois existe
                    if ($mois) {
                        // Ajouter le nom du mois à l'array
                        $nomsMoisCoches[] = $mois->nom_moiscontrat;
                    }
                }
    
            }
                // Vérifier que le tableau n'est pas vide pour éviter une division par zéro
                if (count($nomsMoisCoches) > 0) {
                    $montantmoiscontrat = $montanttotal / count($nomsMoisCoches);
                } else {
                    $montantmoiscontrat = 0; // Ou une autre valeur par défaut
                }

                // $jojo = count($nomsMoisCoches);
                // dd($montanttotal);

            // Convertir le tableau en une chaîne de caractères
            $moisConcatenes = implode(',', $nomsMoisCoches);
    
    
                        // GESTION DE LA FACTURE NORMALISE
    
                    $matriculeeleve = Contrat::where('id_contrat', $idcontratEleve)->value('eleve_contrat');
                    $nomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('NOM');
                    $prenomeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('PRENOM');
                    $classeeleve = Eleve::where('MATRICULE', $matriculeeleve)->value('CODECLAS');
                    $nomcompleteleve = $nomeleve .' '. $prenomeleve;
    
                    $parametrefacture = Params2::first();
                    $ifuentreprise = $parametrefacture->ifu;
                    $tokenentreprise = $parametrefacture->token;
                    $taxe = $parametrefacture->taxe;
                    $type = $parametrefacture->typefacture;
                    // dd($ifuentreprise);
                    $parametreetab = Params2::first();
                    // $nometab = $parametreetab->NOMETAB;
                    // $villeetab = $parametreetab->VILLE;
    
    
    
    
                    // -------------------------------
                        //  CREATION DE LA FACTURE
                    // -------------------------------
                    $items = []; // Initialiser un tableau vide pour les éléments

                    foreach ($nomsMoisCoches as $idmois => $mois) {
                        $items[] = [
                            'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                            'price' => intval($montantmoiscontrat),
                            'quantity' => 1,
                            'taxGroup' => $taxe,
                        ];
                    }

                    // dd($items);
                        // Préparez les données JSON pour l'API
                            $jsonData = json_encode([
                                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                // "aib" => "A",
                                "type" => $type,
                                "items" => $items,
                                "client" => [
                                    // "ifu" => " ",
                                    "name"=>  $nomcompleteleve,
                                    // "contact" => "string",
                                    // "address"=> "string"
                                ],
                                "operator" => [
                                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                ],
                                "payment" => [
                                    [
                                    "name" => "ESPECES",
                                      "amount" => intval($montanttotal)
                                    ]
                                  ],
                            ]);
                        // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
    
    
                        //  dd($jsonData);
    
                        // Définissez l'URL de l'API de facturation
                        $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
    
                        // Définissez le jeton d'authentification
                        $token = $tokenentreprise;
                        // $token = $tokenentreprise;
    
                        // Effectuez la requête POST à l'API
                        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ));
                        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                    // Exécutez la requête cURL et récupérez la réponse
            $response = curl_exec($ch);
            // dd($response);
    
            // Vérifiez les erreurs de cURL
            if (curl_errno($ch)) {
                // echo 'Erreur cURL : ' . curl_error($ch);
                return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
            }

            // {
            //     "ifu": "string",
            //     "aib": "A",
            //     "type": "FV",
            //     "items": [
            //       {
            //         "code": "string",
            //         "name": "string",
            //         "price": 0,
            //         "quantity": 0,
            //         "taxGroup": "A",
            //         "taxSpecific": 0,
            //         "originalPrice": 0,
            //         "priceModification": "string"
            //       }
            //     ],
            //     "client": {
            //       "ifu": "string",
            //       "name": "string",
            //       "contact": "string",
            //       "address": "string"
            //     },
            //     "operator": {
            //       "id": "string",
            //       "name": "string"
            //     },
            //     "payment": [
            //       {
            //         "name": "ESPECES",
            //         "amount": 0
            //       }
            //     ],
            //     "reference": "string",
            //     "innat": "NA",
            //     "usconf": true
            //   }
    
            // Fermez la session cURL
            curl_close($ch);
    
            // Affichez la réponse de l'API
            $decodedResponse = json_decode($response, true);
            //  dd($decodedResponse);
    
            // Vérifiez si l'UID est présent dans la réponse
            if (isset($decodedResponse['uid'])) {
                // L'UID de la demande
                $uid = $decodedResponse['uid'];
                // $taxb = 0.18;
    
                // Affichez l'UID
                // echo "L'UID de la demande est : $uid";
    
               
    

                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

                // Définissez l'URL de l'API de confirmation de facture
                $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
                // Configuration de la requête cURL pour la confirmation
                $chRecuperation = curl_init($recuperationUrl);
                curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                // Exécutez la requête cURL pour la confirmation
                $responseRecuperation = curl_exec($chRecuperation);
                // dd($responseConfirmation);
                // Vérifiez les erreurs de cURL pour la confirmation

    
                // Fermez la session cURL pour la confirmation
                curl_close($chRecuperation);
    
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedDonneFacture = json_decode($responseRecuperation, true);
    
            // $facturedetaille = json_decode($jsonData, true);
            $ifuEcoleFacture = $decodedDonneFacture['ifu'];
            // dd($ifuEcoleFacture);
            $itemFacture = $decodedDonneFacture['items'];
            $doneeDetailleItemFacture = $itemFacture['0'];
            $nameItemFacture = $doneeDetailleItemFacture['name'];
            $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
            $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
            $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
            // $idd = $responseRecuperation.ifu;
            $nameClient = $decodedDonneFacture['client']['name'];
            // dd($decodedDonneFacture);



                // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

                 // ACTION pour la confirmation
                 $actionConfirmation = 'confirm';

                // Définissez l'URL de l'API de confirmation de facture
                $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
            
                // Configuration de la requête cURL pour la confirmation
                $chConfirmation = curl_init($confirmationUrl);
                curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
            
                // Exécutez la requête cURL pour la confirmation
                $responseConfirmation = curl_exec($chConfirmation);
            
            
                // Fermez la session cURL pour la confirmation
                curl_close($chConfirmation);
            
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedResponseConfirmation = json_decode($responseConfirmation, true);
            // dd($decodedResponseConfirmation);

    
                $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];
    
                $counters = $decodedResponseConfirmation['counters'];
    
                $nim = $decodedResponseConfirmation['nim'];
    
                $dateTime = $decodedResponseConfirmation['dateTime'];
    
    
                // Générer le code QR
                $qrCodeString = $decodedResponseConfirmation['qrCode'];
    
                $reffactures = $nim.'-'.$counters;
                // explode('/', $chaine)[0]

                $reffacture = explode('/', $reffactures)[0];
    
                // dd($reffacture);

            // gestion du code qr sous forme d'image
    
            // $fileNameqrcode = $nomcompleteleve . time() . '.png';
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($qrCodeString)
                ->size(100)
                // ->margin(10)
                ->build();
    
                $qrcodecontent = $result->getString();
    
                // dd($qrcodecontent);

                $TOTALHT = $montanttotal / 1.18;
                $totalHTArrondi = round($TOTALHT);
                $TOTALTVA =$montanttotal - $totalHTArrondi;

                // dd($montanttotal);

            // ENREGISTREMENT DE LA FACTURE
            $facturenormalise = new Facturenormalise();
            $facturenormalise->id = $reffacture;
            $facturenormalise->codemecef = $codemecef;
            $facturenormalise->counters = $counters;
            $facturenormalise->nim = $nim;
            $facturenormalise->dateHeure = $dateTime;
            $facturenormalise->ifuEcole = $ifuEcoleFacture;
            $facturenormalise->MATRICULE = $matriculeeleve;
            $facturenormalise->idcontrat = $idcontratEleve;
            $facturenormalise->moispayes = $moisConcatenes;
            $facturenormalise->classe = $classeeleve;
            $facturenormalise->nom = $nameClient;
            $facturenormalise->designation = 'Frais cantine pour: '.$moisConcatenes;
            $facturenormalise->montant_total = $montanttotal;
            $facturenormalise->TOTALHT = $totalHTArrondi;
            $facturenormalise->TOTALTVA = $TOTALTVA;
            $facturenormalise->montant_par_mois = $prixTotalItemFacture;
            $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
            $facturenormalise->qrcode = $qrcodecontent;
            $facturenormalise->statut = 1;
        
            $facturenormalise->save();

    
             } 
    
    
    }

    public function listefacture() {
        $facturesPaiement = DB::table('facturenormalises')->where('statut', 1)->get();
        $facturesInscription = DB::table('facturenormaliseinscription')->where('statut', 1)->get();
        // dd($factures);
        // return view('pages.Etats.listefacture')->with('factures', $factures);
        return view('pages.Etats.listefacture', compact('facturesPaiement', 'facturesInscription'));

    }

    public function listeFacturesAvoir() {

        $facturesAvoirPaiement = DB::table('facturenormalises')
        ->whereRaw("RIGHT(counters, 2) = 'FA'")
        ->get();
    
        $facturesAvoirInscription = DB::table('facturenormaliseinscription')
        ->whereRaw("RIGHT(counters, 2) = 'FA'")
        ->get();
    
        // dd($facturesAvoirPaiement);
        return view('pages.Etats.listeFacturesAvoir', compact('facturesAvoirPaiement', 'facturesAvoirInscription'));

    }

    // public function listefactureinscription() {
    //     $factures = DB::table('facturenormaliseinscription')->where('statut', 1)->get();
    //     // dd($factures);
    //     return view('pages.Etats.listefactureinscription')->with('factures', $factures);
    // }

    public function avoirfacturepaie($codemecef) {
        $factureOriginale = DB::table('facturenormalises')->where('codemecef', $codemecef)->first();
        // dd($factures);
        return view('pages.Etats.avoirfacturepaie')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef);
    }

    public function avoirfacturepaiemodif($codemecef) {
        $factureOriginale = DB::table('facturenormalises')->where('codemecef', $codemecef)->first();
        // dd($factures);

        $elev = Eleve::get();
        $pramcontrat = Paramcontrat::first();
    // Récupérer les matricules des élèves dont le statut de contrat est égal à 1
    $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

    // Récupérer les noms et prénoms des élèves correspondants
    $eleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
        ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
        ->orderBy('NOM', 'asc')
        ->get();
        // dd($eleves);

        // Les noms des classes à exclure
        // $classesAExclure = ['NON', 'DELETE'];

        // Récupérer toutes les classes sauf celles à exclure
        $classes = Classes::where('TYPECLASSE', 1)->get();
        $classes = Classes::get();
        // $fraiscontrat = Paramcontrat::first(); 
        // Session::put('eleves', $eleves);
        // Session::put('classes', $classes);
        // Session::put('fraiscontrats', $fraiscontrat);
        // Convertir une chaîne de latin1 à UTF-8

        // -------------------------------------------------

                // // Liste des mots à exclure
                // $excludedWords = ["DELETE", 'NON'];
                    
                // // Construire la requête initiale
                // $query = Classes::query();

                // // Ajouter des conditions pour exclure les mots
                // foreach ($excludedWords as $word) {
                //     $query->where('CODECLAS', 'not like', '%' . $word . '%');
                // }

                // // Récupérer les résultats
                // $class = $query->get();
               

        // -------------------------------------------------

        $montantMensuelDefaut = $pramcontrat->coutmensuel_paramcontrat;
         // Montants fixes pour certains mois
         $montantsFixes = [
            'Septembre' => $montantMensuelDefaut,
            'Decembre' => $montantMensuelDefaut,
            'Avril' => $montantMensuelDefaut,
        ];


        // // Récupérer les mois d'inscription du contrat de l'élève
        // $inscriptioncontrats = Inscriptioncontrat::where('id_contrat', $idcontrateleve)
        //     ->pluck('id_moiscontrat')
        //     ->toArray();

        // Récupérer tous les mois disponibles et les limiter aux 12 premiers mois
        $allmoiscontrat = Moiscontrat::take(12)
            ->pluck('nom_moiscontrat', 'id_moiscontrat')
            ->toArray();

        // Définir l'ordre des mois de septembre à août
        $order = [
            'Septembre', 'Octobre', 'Novembre', 'Decembre',
            'Janvier', 'Fevrier', 'Mars', 'Avril',
            'Mai', 'Juin'
        ];

        // Filtrer les mois qui ne sont pas dans les contrats d'inscription
        $moisNonPayes = $allmoiscontrat;

        $moisAvecMontants = [];
        foreach ($moisNonPayes as $id => $nom) {
            // Utiliser le montant fixe si le mois est dans $montantsFixes, sinon le montant par défaut
            // $montantTotal = $montantsFixes[$nom] ?? $montantMensuelDefaut;
            // $montantPaye = $montantsPayes[$nom] ?? 0; // Si aucun paiement n'a été fait, utiliser 0
            // $montantRestant = $montantTotal - $montantPaye;


            // Ajouter les informations du mois et le montant restant
            $moisAvecMontants[$id] = [
                'nom' => $nom,
                // 'montant_restant' => $montantRestant
            ];
        }

         // Réorganiser les mois selon l'ordre défini
         $moisCorrespondants = [];
         foreach ($order as $mois) {
             foreach ($moisAvecMontants as $id => $info) {
                 if ($info['nom'] === $mois) {
                     $moisCorrespondants[$id] = $info['nom'];
                     break;
                 }
             }
         }


        // Réorganiser les mois selon l'ordre défini
        // $moisCorrespondants = $moisNonPayes;

        
        $fraismensuelle = $pramcontrat->coutmensuel_paramcontrat;
        // ->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat)->with('elev', $elev)->with('moisCorrespondants', $moisCorrespondants)->with('fraismensuelle', $fraismensuelle);
        return view('pages.Etats.avoirfacturepaiemodif')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef)->with('classe', $classes)->with('eleves',  $eleves)->with('moisCorrespondants', $moisCorrespondants)->with('fraismensuelle', $fraismensuelle);
    }

    public function avoirfactureinscri($codemecef) {
        $factureOriginale = DB::table('facturenormaliseinscription')->where('codemecef', $codemecef)->first();
        // dd($factures);
        return view('pages.Etats.avoirfactureinscri')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef);
    }

    public function avoirfacture(Request $request, $codemecef){
        // dd('code correct');
        $codemecefEntrer = $request->input('inputCodemecef');
        if ($codemecefEntrer == $codemecef) {
            // dd('codemecef correct');
            $infoparam = Params2::first();
            $tokenentreprise = $infoparam->token;
    
            $factureoriginale = DB::table('facturenormalises')->where('codemecef', $codemecef)->first();
            $ifuentreprise = $factureoriginale->ifuEcole;
            $montanttotal = $factureoriginale->montant_total;
            $TOTALTVA = $factureoriginale->TOTALTVA;
            $TOTALHT = $factureoriginale->TOTALHT;
    
            $nomcompleteleve = $factureoriginale->nom;
            $moisConcatenes = $factureoriginale->moispayes;
            $matriculeeleve = $factureoriginale->MATRICULE;
            $idcontratEleve = $factureoriginale->idcontrat;
            $classeeleve = $factureoriginale->classe;
            $montantparmois = $factureoriginale->montant_par_mois;
            $datepaiementcontrat = $factureoriginale->datepaiementcontrat;
            $typefac = $factureoriginale->typefac;
            $montantInscription = $factureoriginale->montantInscription;
            // dd($moisConcatenes);
                        // -------------------------------
                            //  CREATION DE LA FACTURE
                        // -------------------------------
                            // dd($moisConcatenes);
                            $moisArray = explode(',', $moisConcatenes);
                            // dd($moisArray);
    
                            $items = []; // Initialiser un tableau vide pour les éléments
    
                            if ($typefac === 1) {
                                // $montanttotal = $montanttotal + $montantInscription;
                                // AJOUT D’UNE LIGNE FIXE POUR INSCRIPTION
                                $items[] = [
                                    'name'      => 'Frais cantine pour inscription',
                                    'price'     => intval($montantInscription),
                                    'quantity'  => 1,
                                    'taxGroup'  => 'E',
                                ];
                            }
                            foreach ($moisArray as $idmois => $mois) {
                                $items[] = [
                                    'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                                    'price' => intval($montantparmois),
                                    'quantity' => 1,
                                    'taxGroup' => 'E',
                                ];
                            }
    
                            // Préparez les données JSON pour l'API
                            $jsonData = json_encode([
                                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                // "aib" => "A",
                                "type" => 'FA',
                                "items" => $items,
                                "client" => [
                                    // "ifu" => '',
                                    "name"=>  $nomcompleteleve,
                                    // "contact" => "string",
                                    // "address"=> "string"
                                ],
                                "operator" => [
                                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                ],
                                "payment" => [
                                    [
                                    "name" => "ESPECES",
                                      "amount" => intval($montanttotal), 
                                    ]
                                  ],
                                  "reference" => $codemecef,
                            ]);
                        // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
    
    
                        //  dd($jsonData);
    
                        // Définissez l'URL de l'API de facturation
                        $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
    
                        // Définissez le jeton d'authentification
                        // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODU5MTExMzh8VFMwMTAxMTQ3MiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyNDI1NzQyMywiZXhwIjoxNzM3NDE0MDAwLCJpYXQiOjE3MjQyNTc0MjMsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.sRcSeEbIuQNSgFebRRaxW4zPLCqlF6PQXc90e2xfHCs';
                        $token = $tokenentreprise;
    
                        // Effectuez la requête POST à l'API
                        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ));
                        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                    // Exécutez la requête cURL et récupérez la réponse
            $response = curl_exec($ch);
            // dd($response);
    
            $decodedResponse = json_decode($response, true);
    
                // Vérifiez si l'UID est présent dans la réponse
                if (isset($decodedResponse['uid'])) {
                    // L'UID de la demande
                    $uid = $decodedResponse['uid'];
                    // $taxb = 0.18;
    
                                    // -------------------------------
                        //  RECUPERATION DE LA FACTURE PAR SON UID
                    // -------------------------------
    
                    // Définissez l'URL de l'API de confirmation de facture
                    $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
        
                    // Configuration de la requête cURL pour la confirmation
                    $chRecuperation = curl_init($recuperationUrl);
                    curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Length: 0'
                    ]);
                    curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
        
                    // Exécutez la requête cURL pour la confirmation
                    $responseRecuperation = curl_exec($chRecuperation);
                    // dd($responseRecuperation);
                    // Vérifiez les erreurs de cURL pour la confirmation
    
        
                    // Fermez la session cURL pour la confirmation
                    curl_close($chRecuperation);
        
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedDonneFacture = json_decode($responseRecuperation, true);
        
                // $facturedetaille = json_decode($jsonData, true);
                $ifuEcoleFacture = $decodedDonneFacture['ifu'];
                // dd($ifuEcoleFacture);
                $itemFacture = $decodedDonneFacture['items'];
                $jsonItems = json_encode($itemFacture);
                $doneeDetailleItemFacture = $itemFacture['0'];
                $nameItemFacture = $doneeDetailleItemFacture['name'];
                $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
                $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
                $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
                // $idd = $responseRecuperation.ifu;
                $nameClient = $decodedDonneFacture['client']['name'];
                // dd($itemFacture);
    
                 // -------------------------------
                        //  CONFIRMATION DE LA FACTURE 
                    // -------------------------------
    
                     // ACTION pour la confirmation
                     $actionConfirmation = 'confirm';
    
                    // Définissez l'URL de l'API de confirmation de facture
                    $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
                
                    // Configuration de la requête cURL pour la confirmation
                    $chConfirmation = curl_init($confirmationUrl);
                    curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Length: 0'
                    ]);
                    curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
                
                    // Exécutez la requête cURL pour la confirmation
                    $responseConfirmation = curl_exec($chConfirmation);
                
                
                    // Fermez la session cURL pour la confirmation
                    curl_close($chConfirmation);
                
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedResponseConfirmation = json_decode($responseConfirmation, true);
                // dd($decodedResponseConfirmation);
    
        
                    $codemecefavoir = $decodedResponseConfirmation['codeMECeFDGI'];
        
                    $counters = $decodedResponseConfirmation['counters'];
        
                    $nim = $decodedResponseConfirmation['nim'];
        
                    $dateTime = $decodedResponseConfirmation['dateTime'];
        
                          // Générer le code QR
                          $qrCodeString = $decodedResponseConfirmation['qrCode'];
        
                          $reffactures = $nim.'-'.$counters;
    
                          $reffacture = explode('/', $reffactures)[0];
    
                          
              
                          // dd($reffacture);
          
                      // gestion du code qr sous forme d'image
              
                      // $fileNameqrcode = $nomcompleteleve . time() . '.png';
                      $result = Builder::create()
                          ->writer(new PngWriter())
                          ->data($qrCodeString)
                          ->size(100)
                          // ->margin(10)
                          ->build();
              
                          $qrcodecontent = $result->getString();
    
                        //   dd($jsonItems);
              
         // ENREGISTREMENT DE LA FACTURE
         $facturenormalise = new Facturenormalise();
         $facturenormalise->id = $reffacture;
         $facturenormalise->codemecef = $codemecefavoir;
         $facturenormalise->codemeceffacoriginale = $codemecef;
         $facturenormalise->counters = $counters;
         $facturenormalise->nim = $nim;
         $facturenormalise->dateHeure = $dateTime;
         $facturenormalise->ifuEcole = $ifuEcoleFacture;
         $facturenormalise->MATRICULE = $matriculeeleve;
         $facturenormalise->idcontrat = $idcontratEleve;
         $facturenormalise->moispayes = $moisConcatenes;
         $facturenormalise->itemfacture = $jsonItems;
         $facturenormalise->classe = $classeeleve;
         $facturenormalise->nom = $nameClient;
         $facturenormalise->designation = $nameItemFacture;
         $facturenormalise->montant_total = $montanttotal;
         $facturenormalise->TOTALTVA = intval($TOTALTVA);
         $facturenormalise->TOTALHT = intval($TOTALHT);
         $facturenormalise->montant_par_mois = intval($montantparmois);
        //  $facturenormalise->montant_total = $prixTotalItemFacture;
         $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
         $facturenormalise->qrcode = $qrcodecontent;
         $facturenormalise->statut = 0;
         $facturenormalise->typefac = $typefac;
     
         $facturenormalise->save();

         DB::table('facturenormalises')
            ->where('codemecef', $codemecef)
            ->update(['statut' => 0]);
    
        //  $factureoriginale->statut = 0;
        //  $factureoriginale->update();  
    
    
    
    
        // effacer les donnes de la facture qui sont dans paiementcontrat et paiementglobalcontrat

         // Suupression des mois dans inscriptioncontrat
         $moisLibellé = explode(',', $moisConcatenes); 
         // Résultat : ['Novembre', 'Janvier']

         // 2) Récupérer les IDs en base pour ces noms
         $moisIdentifiant = Moiscontrat::whereIn('nom_moiscontrat', $moisLibellé)
             ->pluck('id_moiscontrat')  // ou 'id' selon ta colonne PK
             ->toArray();

             Inscriptioncontrat::where('id_contrat', $idcontratEleve)
             ->whereIn('id_moiscontrat', $moisIdentifiant)
             ->delete();
    
        // MISE À JOUR DES LIGNES DANS paiementcontrat
        Paiementcontrat::where('id_contrat', $idcontratEleve)
            ->where('date_paiementcontrat', $datepaiementcontrat)
            ->update(['statut_paiementcontrat' => 0]);

        // MISE À JOUR DES LIGNES DANS paiementglobalcontrat
        Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
            ->where('date_paiementcontrat', $datepaiementcontrat)
            ->update(['statut_paiementcontrat' => 0]);
    
            if ( (int) $typefac === 1){

            DB::table('paiementcontrat')
                ->where('id_contrat', $idcontratEleve)
                ->delete();

            // (si tu as aussi une table globale…)
            DB::table('paiementglobalcontrat')
                ->where('id_contrat', $idcontratEleve)
                ->delete();

                DB::table('contrat')
                    ->where('id_contrat', $idcontratEleve)
                    ->delete();
            }
    
                return back()->with('status', "Facture d'avoir generer avec succes");
            
            
                }
            } else {
                // dd('codemecef incorrect');
                return back()->with('erreur', "Le codemecef entrer ne correspond pas a celui de la facture originale.");

            }

            
        
    }

    // avoire facture paiement et modification
    public function avoirfactureetmodification(Request $request, $codemecef){
        // dd('code correct');
        $id_usercontrat = Session::get('id_usercontrat');
        $codemecefEntrer = $request->input('inputCodemecef');
        $typeFormulaire = $request->input('typeFormulaire');
        if ($codemecefEntrer == $codemecef) {
            // dd('codemecef correct');
            $infoparam = Params2::first();
            $tokenentreprise = $infoparam->token;
            $taxe = $infoparam->taxe;
            $type = $infoparam->typefacture;

            
            $infoParamContrat = Paramcontrat::first();
            $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
    
            $factureoriginale = DB::table('facturenormalises')->where('codemecef', $codemecef)->first();
            $ifuentreprise = $factureoriginale->ifuEcole;
            $montanttotal = $factureoriginale->montant_total;
            $TOTALTVA = $factureoriginale->TOTALTVA;
            $TOTALHT = $factureoriginale->TOTALHT;
    
            $nomcompleteleve = $factureoriginale->nom;
            $moisConcatenes = $factureoriginale->moispayes;
            $matriculeeleve = $factureoriginale->MATRICULE;
            $idcontratEleve = $factureoriginale->idcontrat;
            $classeeleve = $factureoriginale->classe;
            $montantparmois = $factureoriginale->montant_par_mois;
            $montantTotalFacOriginal = $factureoriginale->montant_total;
            $datepaiementcontrat = $factureoriginale->datepaiementcontrat;
            $typefac = $factureoriginale->typefac;
            $itemFacOriginale = $factureoriginale->itemfacture;
            $montantInscription = $factureoriginale->montantInscription;
            $datepaiementcontratNouveau = $request->input('date');

            // convertir le tableau json itemFacOriginale en tableau php

            $itemFacOriginaleArray = json_decode($itemFacOriginale, true);
            foreach ($itemFacOriginaleArray as $item) {
                // $item['name'], $item['price'], etc.
                // Par exemple, pour reformer un nouveau tableau :
                $nouveauxItems[] = [
                    'name'     => $item['name'],
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                    'taxGroup' => $item['taxGroup'],
                    // tu peux ajouter ou modifier des clés si besoin
                ];
            }
            // dd($nouveauxItems);
            // dd($nouveauxItems);
                        // -------------------------------
                            //  CREATION DE LA FACTURE
                        // -------------------------------
                            // dd($moisConcatenes);
                            $moisArray = explode(',', $moisConcatenes);
                            // dd($moisArray);
    
                            $items = []; // Initialiser un tableau vide pour les éléments
    
                            if ($typefac === 1) {
                                // $montanttotal = $montanttotal + $montantInscription;
                                // AJOUT D’UNE LIGNE FIXE POUR INSCRIPTION
                                $items[] = [
                                    'name'      => 'Frais cantine pour inscription',
                                    'price'     => intval($montantInscription),
                                    'quantity'  => 1,
                                    'taxGroup'  => 'E',
                                ];
                            }
                            foreach ($moisArray as $idmois => $mois) {
                                $items[] = [
                                    'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                                    'price' => intval($montantparmois),
                                    'quantity' => 1,
                                    'taxGroup' => 'E',
                                ];
                            }
    
                            // Préparez les données JSON pour l'API
                            $jsonData = json_encode([
                                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                // "aib" => "A",
                                "type" => 'FA',
                                "items" => $items,
                                "client" => [
                                    // "ifu" => '',
                                    "name"=>  $nomcompleteleve,
                                    // "contact" => "string",
                                    // "address"=> "string"
                                ],
                                "operator" => [
                                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                ],
                                "payment" => [
                                    [
                                    "name" => "ESPECES",
                                      "amount" => intval($montanttotal), 
                                    ]
                                  ],
                                  "reference" => $codemecef,
                            ]);
                        // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
    
    
                        //  dd($jsonData);
    
                        // Définissez l'URL de l'API de facturation
                        $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
    
                        // Définissez le jeton d'authentification
                        // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODU5MTExMzh8VFMwMTAxMTQ3MiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyNDI1NzQyMywiZXhwIjoxNzM3NDE0MDAwLCJpYXQiOjE3MjQyNTc0MjMsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.sRcSeEbIuQNSgFebRRaxW4zPLCqlF6PQXc90e2xfHCs';
                        $token = $tokenentreprise;
    
                        // Effectuez la requête POST à l'API
                        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ));
                        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                    // Exécutez la requête cURL et récupérez la réponse
            $response = curl_exec($ch);
            // dd($response);
    
            $decodedResponse = json_decode($response, true);
    
                // Vérifiez si l'UID est présent dans la réponse
                if (isset($decodedResponse['uid'])) {
                    // L'UID de la demande
                    $uid = $decodedResponse['uid'];
                    // $taxb = 0.18;
    
                                    // -------------------------------
                        //  RECUPERATION DE LA FACTURE PAR SON UID
                    // -------------------------------
    
                    // Définissez l'URL de l'API de confirmation de facture
                    $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
        
                    // Configuration de la requête cURL pour la confirmation
                    $chRecuperation = curl_init($recuperationUrl);
                    curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Length: 0'
                    ]);
                    curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
        
                    // Exécutez la requête cURL pour la confirmation
                    $responseRecuperation = curl_exec($chRecuperation);
                    // dd($responseRecuperation);
                    // Vérifiez les erreurs de cURL pour la confirmation
    
        
                    // Fermez la session cURL pour la confirmation
                    curl_close($chRecuperation);
        
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedDonneFacture = json_decode($responseRecuperation, true);
        
                // $facturedetaille = json_decode($jsonData, true);
                $ifuEcoleFacture = $decodedDonneFacture['ifu'];
                // dd($ifuEcoleFacture);
                $itemFacture = $decodedDonneFacture['items'];
                $jsonItems = json_encode($itemFacture);
                $doneeDetailleItemFacture = $itemFacture['0'];
                $nameItemFacture = $doneeDetailleItemFacture['name'];
                $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
                $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
                $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
                // $idd = $responseRecuperation.ifu;
                $nameClient = $decodedDonneFacture['client']['name'];
                // dd($itemFacture);
    
                 // -------------------------------
                        //  CONFIRMATION DE LA FACTURE 
                    // -------------------------------
    
                     // ACTION pour la confirmation
                     $actionConfirmation = 'confirm';
    
                    // Définissez l'URL de l'API de confirmation de facture
                    $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
                
                    // Configuration de la requête cURL pour la confirmation
                    $chConfirmation = curl_init($confirmationUrl);
                    curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Length: 0'
                    ]);
                    curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
                
                    // Exécutez la requête cURL pour la confirmation
                    $responseConfirmation = curl_exec($chConfirmation);
                
                
                    // Fermez la session cURL pour la confirmation
                    curl_close($chConfirmation);
                
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedResponseConfirmation = json_decode($responseConfirmation, true);
                // dd($decodedResponseConfirmation);
    
        
                    $codemecefavoir = $decodedResponseConfirmation['codeMECeFDGI'];
        
                    $counters = $decodedResponseConfirmation['counters'];
        
                    $nim = $decodedResponseConfirmation['nim'];
        
                    $dateTime = $decodedResponseConfirmation['dateTime'];
        
                          // Générer le code QR
                          $qrCodeString = $decodedResponseConfirmation['qrCode'];
        
                          $reffactures = $nim.'-'.$counters;
    
                          $reffacture = explode('/', $reffactures)[0];
    
                          
              
                          // dd($reffacture);
          
                      // gestion du code qr sous forme d'image
              
                      // $fileNameqrcode = $nomcompleteleve . time() . '.png';
                      $result = Builder::create()
                          ->writer(new PngWriter())
                          ->data($qrCodeString)
                          ->size(100)
                          // ->margin(10)
                          ->build();
              
                          $qrcodecontent = $result->getString();
    
                        //   dd($jsonItems);
              
         // ENREGISTREMENT DE LA FACTURE
         $facturenormalise = new Facturenormalise();
         $facturenormalise->id = $reffacture;
         $facturenormalise->codemecef = $codemecefavoir;
         $facturenormalise->codemeceffacoriginale = $codemecef;
         $facturenormalise->counters = $counters;
         $facturenormalise->nim = $nim;
         $facturenormalise->dateHeure = $dateTime;
         $facturenormalise->ifuEcole = $ifuEcoleFacture;
         $facturenormalise->MATRICULE = $matriculeeleve;
         $facturenormalise->idcontrat = $idcontratEleve;
         $facturenormalise->moispayes = $moisConcatenes;
         $facturenormalise->itemfacture = $jsonItems;
         $facturenormalise->classe = $classeeleve;
         $facturenormalise->nom = $nameClient;
         $facturenormalise->designation = $nameItemFacture;
         $facturenormalise->montant_total = $montanttotal;
         $facturenormalise->TOTALTVA = intval($TOTALTVA);
         $facturenormalise->TOTALHT = intval($TOTALHT);
         $facturenormalise->montant_par_mois = intval($montantparmois);
        //  $facturenormalise->montant_total = $prixTotalItemFacture;
         $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
         $facturenormalise->qrcode = $qrcodecontent;
         $facturenormalise->statut = 0;
         $facturenormalise->typefac = $typefac;
     
         $facturenormalise->save();

         DB::table('facturenormalises')
            ->where('codemecef', $codemecef)
            ->update(['statut' => 0]);

            // Suupression des mois dans inscriptioncontrat
            $moisLibellé = explode(',', $moisConcatenes); 
            // Résultat : ['Novembre', 'Janvier']

            // 2) Récupérer les IDs en base pour ces noms
            $moisIdentifiant = Moiscontrat::whereIn('nom_moiscontrat', $moisLibellé)
                ->pluck('id_moiscontrat')  // ou 'id' selon ta colonne PK
                ->toArray();

                Inscriptioncontrat::where('id_contrat', $idcontratEleve)
                ->whereIn('id_moiscontrat', $moisIdentifiant)
                ->delete();

    

        // verifier le type d'action choisie 
        switch ($typeFormulaire) {
            case 'corriger_eleve':
                // dd('corriger_eleve');
                // creer la meme facture pour un nouveau eleve
                $matriculeNouvEleve = $request->input('eleve');
                $informationNouvEleve = Eleve::where('MATRICULE', $matriculeNouvEleve)->first();
                $nomCompletNouvEleve = $informationNouvEleve->NOM .' ' . $informationNouvEleve->PRENOM;
                $classeNouvEleve = $informationNouvEleve->CODECLAS;

                $contratNouvEleve = Contrat::where('eleve_contrat', $matriculeNouvEleve)->first();
                $idContratNouvEleve = $contratNouvEleve->id_contrat;

                // MISE À JOUR DES LIGNES DANS paiementcontrat
                // Paiementcontrat::where('id_contrat', $idContratNouvEleve)
                // ->where('date_paiementcontrat', $datepaiementcontrat)
                // ->update([
                //     'date_paiementcontrat'     => $datepaiementcontratNouveau,
                // ]);
            
                // Paiementglobalcontrat::where('id_contrat', $idContratNouvEleve)
                //     ->where('date_paiementcontrat', $datepaiementcontrat)
                //     ->update([
                //         'date_paiementcontrat'     => $datepaiementcontratNouveau,
                //     ]);

                Paiementcontrat::where('id_contrat', $idcontratEleve)
                ->where('date_paiementcontrat', $datepaiementcontrat)
                ->update([
                    'id_contrat'             => $idContratNouvEleve,
                    'date_paiementcontrat'   => $datepaiementcontratNouveau,
                ]);

                Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
                    ->where('date_paiementcontrat', $datepaiementcontrat)
                    ->update([
                        'id_contrat'             => $idContratNouvEleve,
                        'date_paiementcontrat'   => $datepaiementcontratNouveau,
                    ]);
                // debut de la creation de la facture

                  // -------------------------------
                        //  CREATION DE LA FACTURE
                    // -------------------------------

                  
                    // dd($items);
            // Préparez les données JSON pour l'API
            $jsonData = json_encode([
                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                // "aib" => "A",
                "type" => $type,
                "items" => $nouveauxItems,

                "client" => [
                    // "ifu" => '',
                    "name"=>  $nomCompletNouvEleve,
                    // "contact" => "string",
                    // "address"=> "string"
                ],
                "operator" => [
                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                ],
                "payment" => [
                    [
                    "name" => "ESPECES",
                      "amount" => intval($montantTotalFacOriginal)
                    ]
                  ],
            ]);
            // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


            // dd($jsonData);
    
            // Définissez l'URL de l'API de facturation
            $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
            
    
            // Définissez le jeton d'authentification

                $token = $tokenentreprise;
            //    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjEyMDEyMDE1NzQ1MDJ8VFMwMTAxMjE5OCIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyOTAwMTYwNiwiZXhwIjoxOTI0OTAyMDAwLCJpYXQiOjE3MjkwMDE2MDYsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.t0VBBtOig972FWCW2aFk7jyb-SHKv1iSZ9bkM-IGc2M";
                // $token = $tokenentreprise;
    
            // Effectuez la requête POST à l'API
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
            // Exécutez la requête cURL et récupérez la réponse
        $response = curl_exec($ch);
        // dd($response);
        
        // Vérifiez les erreurs de cURL
        if (curl_errno($ch)) {
            // echo 'Erreur cURL : ' . curl_error($ch);
            return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
        }
        
        // Fermez la session cURL
        curl_close($ch);
        
        // Affichez la réponse de l'API
        $decodedResponse = json_decode($response, true);
        // dd($decodedResponse);
        
        // Vérifiez si l'UID est présent dans la réponse
        if (isset($decodedResponse['uid'])) {
            // L'UID de la demande
            $uid = $decodedResponse['uid'];
            // $taxb = 0.18;
    
        // Affichez l'UID
        // echo "L'UID de la demande est : $uid";

        
                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

            // Définissez l'URL de l'API de confirmation de facture
            $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
            // Configuration de la requête cURL pour la confirmation
            $chRecuperation = curl_init($recuperationUrl);
            curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Length: 0'
            ]);
            curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

            // Exécutez la requête cURL pour la confirmation
            $responseRecuperation = curl_exec($chRecuperation);
            // dd($responseRecuperation);
            // Vérifiez les erreurs de cURL pour la confirmation


            // Fermez la session cURL pour la confirmation
            curl_close($chRecuperation);

        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedDonneFacture = json_decode($responseRecuperation, true);
        // dd($decodedDonneFacture);

        $facturedetaille = json_decode($jsonData, true);
        $ifuEcoleFacture = $decodedDonneFacture['ifu'];
        $itemFacture = $decodedDonneFacture['items'];
        $jsonItem = json_encode($itemFacture);
        $doneeDetailleItemFacture = $itemFacture['0'];
        $nameItemFacture = $doneeDetailleItemFacture['name'];
        $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
        $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
        $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
        // $idd = $responseRecuperation.ifu;
        $nameClient = $decodedDonneFacture['client']['name'];
        // dd($prixTotalItemFacture);

    
                // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

        // ACTION pour la confirmation
        $actionConfirmation = 'confirm';
    
        // Définissez l'URL de l'API de confirmation de facture
        $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
    
        // Configuration de la requête cURL pour la confirmation
        $chConfirmation = curl_init($confirmationUrl);
        curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Length: 0'
        ]);
        curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
        // Exécutez la requête cURL pour la confirmation
        $responseConfirmation = curl_exec($chConfirmation);
    
        // Vérifiez les erreurs de cURL pour la confirmation
        if (curl_errno($chConfirmation)) {
            // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
            return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

        }
    
        // Fermez la session cURL pour la confirmation
        curl_close($chConfirmation);
    
        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedResponseConfirmation = json_decode($responseConfirmation, true);
        //   dd($decodedResponseConfirmation);
        
        
        $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

        $counters = $decodedResponseConfirmation['counters'];

        $nim = $decodedResponseConfirmation['nim'];

        $dateTime = $decodedResponseConfirmation['dateTime'];

        // Générer le code QR
        $qrCodeString = $decodedResponseConfirmation['qrCode'];

        $reffactures = $nim.'-'.$counters;

        $reffacture = explode('/', $reffactures)[0];

                // gestion du code qr sous forme d'image

                $fileNameqrcode = $nomCompletNouvEleve . time() . '.png';
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrCodeString)
                    ->size(100)
                    // ->margin(10)
                    ->build();

                    $qrcodecontent = $result->getString();


     
                    
                     
                     
            // ********************************
                     
                     // generer une valeur aleatoire comprise entre 10000000 et 99999999 et verifier si elle existe deja dans la table.
                    // Si la valeur est déjà présente, exists() renverra true, et la boucle continuera à s'exécuter pour générer une nouvelle valeur.
                    // Si la valeur n'est pas présente (c'est-à-dire qu'elle est unique), la condition exists() renverra false, et la boucle s'arrêtera.
                    do {
                        // Génère un nombre aléatoire entre 10000000 et 99999999
                    $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
                    } while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());



        // CALCUL DU TOTALHT ET TOTALTVA

        $TOTALHT = $montanttotal / 1.18;
        $totalHTArrondi = 0;
        $TOTALTVA = 0;

            // ********************************

                    // enregistrer les mois dans inscriptioncontrat

                    
                    // 1) Exploser la chaîne en noms
                    $moisNoms = explode(',', $moisConcatenes); 
                    // Résultat : ['Novembre', 'Janvier']

                    // 2) Récupérer les IDs en base pour ces noms
                    $moisIds = Moiscontrat::whereIn('nom_moiscontrat', $moisNoms)
                        ->pluck('id_moiscontrat')  // ou 'id' selon ta colonne PK
                        ->toArray();

                        // dd($moisIds); 

                        // On récupère directement les noms
                        $moisNoms = Moiscontrat::whereIn('id_moiscontrat', $moisIds)
                        ->pluck('nom_moiscontrat')   // champ qui contient "Janvier", "Fevrier", etc.
                        ->toArray();

                        // dd($moisNoms); 

                    // 1) Charge en une seule requête le mapping ID → nom de mois
                    $moisMap = Moiscontrat::whereIn('id_moiscontrat', $moisIds)
                    ->pluck('nom_moiscontrat', 'id_moiscontrat')
                    ->toArray();

                    // 2) Parcours chaque ID de mois
                    foreach ($moisIds as $id_moiscontrat) {
                    // Récupère le nom correspondant
                    $mois = $moisMap[$id_moiscontrat] ?? null;
                    
                    if (! $mois) {
                        // ID inconnu, on skip
                        continue;
                    }

                    // 3) Calcul du montant à payer (si certains mois ont un traitement spécial)
                    //    Ici l’exemple montre le même montant pour tous mais tu peux adapter :
                    if (in_array($mois, ['Decembre','Septembre','Avril'], true)) {
                        $montantAPayer = 17000;
                    } else {
                        $montantAPayer = 17000;
                    }

                    // 4) Récupère la somme déjà versée pour ce mois
                    $totalDejaPaye = Facturenormalise::where('idcontrat', $idContratNouvEleve)
                        ->where('moispayes', $mois)
                        ->sum('montant_par_mois') 
                        ?? 0;


                    // 5) Calcule le nouveau total si on ajoute ce mois
                    $sommeTotale = $totalDejaPaye + $montantparmois;

                    // 6) Décide si on doit “sauver” ce mois
                    $saveMois = $sommeTotale < $montantAPayer ? 1 : 0;

                    // dd($saveMois);

                    // 7) Si oui, on crée l’inscription
                    if ($saveMois == 0) {
                        Inscriptioncontrat::create([
                            'id_contrat'       => $idContratNouvEleve,
                            'id_moiscontrat'   => $id_moiscontrat,
                            'anne_inscription' => $debutAnneeEnCours,
                            // ajoute ici les autres champs si besoin
                        ]);
                    }
                    }


        
        // dd($ifuEcoleFacture);
                    $facturenormalise = new Facturenormalise();
                    $facturenormalise->id = $reffacture;
                    $facturenormalise->codemecef = $codemecef;
                    $facturenormalise->counters = $counters;
                    $facturenormalise->nim = $nim;
                    $facturenormalise->dateHeure = $dateTime;
                    $facturenormalise->ifuEcole = $ifuEcoleFacture;
                    $facturenormalise->MATRICULE = $matriculeNouvEleve;
                    $facturenormalise->idcontrat = $idContratNouvEleve;
                    $facturenormalise->moispayes = $moisConcatenes;
                    $facturenormalise->classe = $classeNouvEleve;
                    $facturenormalise->nom = $nameClient;
                    $facturenormalise->itemfacture = $jsonItem;
                    $facturenormalise->designation = 'Frais cantine pour: '.$moisConcatenes;
                    $facturenormalise->montant_total = $montanttotal;
                    $facturenormalise->TOTALHT = $totalHTArrondi;
                    $facturenormalise->TOTALTVA = $TOTALTVA;
                    $facturenormalise->montant_par_mois = $montantparmois;
                    $facturenormalise->datepaiementcontrat = $datepaiementcontratNouveau;
                    $facturenormalise->qrcode = $qrcodecontent;
                    $facturenormalise->statut = 1;
                    $facturenormalise->typefac = $typefac;
                    // $facturenormalise->type = "FC";
        
                    $facturenormalise->save();



        $paramse = Params2::first(); 

        $logoUrl = $paramse ? $paramse->logoimage: null; 
    
        $NOMETAB = $paramse->NOMETAB;

        Session::put('factureconfirm', $decodedResponseConfirmation);
        Session::put('fileNameqrcode', $fileNameqrcode);
        Session::put('facturedetaille', $facturedetaille);
        Session::put('reffacture', $reffacture);
        Session::put('classeeleve', $classeNouvEleve);
        Session::put('nomcompleteleve', $nomCompletNouvEleve);
        // Session::put('toutmoiscontrat', $toutmoiscontrat);
        Session::put('nameItemFacture', $nameItemFacture);
        Session::put('prixTotalItemFacture', $prixTotalItemFacture);
        Session::put('quantityItemFacture', $quantityItemFacture);
        Session::put('taxGroupItemFacture', $taxGroupItemFacture);
        Session::put('ifuEcoleFacture', $ifuEcoleFacture);
        Session::put('qrCodeString', $qrCodeString);
        Session::put('itemFacture', $itemFacture);
        Session::put('montanttotal', $montanttotal);
        Session::put('totalHTArrondi', $totalHTArrondi);
        Session::put('TOTALTVA', $TOTALTVA);
        Session::put('montantmoiscontrat', $montantparmois);
        Session::put('qrcodecontent', $qrcodecontent);
        Session::put('NOMETAB', $NOMETAB);
        Session::put('nim', $nim);
        Session::put('datepaiementcontrat', $datepaiementcontratNouveau);
        Session::put('dateTime', $dateTime);
        // Session::put('nometab', $nometab);
        // Session::put('villeetab', $villeetab);



    
        return view('pages.Etats.pdffacture', [
            'factureconfirm' => $decodedResponseConfirmation,
            'fileNameqrcode' => $fileNameqrcode,
            'facturedetaille' => $facturedetaille,
            'reffacture' => $reffacture,
            'ifuEcoleFacture' => $ifuEcoleFacture,
            'nameItemFacture' => $nameItemFacture,
            'prixTotalItemFacture' => $prixTotalItemFacture,
            'quantityItemFacture' => $quantityItemFacture,
            'taxGroupItemFacture' => $taxGroupItemFacture,
            'classeeleve' => $classeNouvEleve,
            'nomcompleteleve' => $nomCompletNouvEleve,
            // 'toutmoiscontrat' => $toutmoiscontrat,
            'qrCodeString' => $qrCodeString,
            'logoUrl' => $logoUrl,
            'itemFacture' => $itemFacture,
            'montanttotal' => $montanttotal,
            'qrcodecontent' => $qrcodecontent,
            'NOMETAB' => $NOMETAB,
            'nim' => $nim,
            'datepaiementcontrat' => $datepaiementcontratNouveau,
            'dateTime' => $dateTime,
            'totalHTArrondi' => $totalHTArrondi,
            'TOTALTVA' => $TOTALTVA,
            // 'villeetab' => $villeetab,
            // 'qrCodeImage' => $qrCodeImage,
    
                 ]);

                }

                break;

            // fin de la creation de la facture


            case 'corriger_mois':
                // dd('corriger_mois');

                // SUPPRIMER LES LIGNE DE PAIEMENTCONTRAT ET PAIEMENTGLOBALCONTRAT
                DB::table('paiementcontrat')
                ->where('id_contrat', $idcontratEleve)
                ->delete();

                DB::table('paiementglobalcontrat')
                    ->where('id_contrat', $idcontratEleve)
                    ->delete();


                    // DEBUT DE LA CREATION DE LA FACTURE



                    $moisCoches = $request->input('moiscontrat');
                    $montantmoiscontrat = $request->input('montantcontrat');
                    $montanttotal = $request->input('montanttotal');
                    $datepaiementcontrat = $request->input('date');
                    $montantParMoisReel = $request->input('montantcontratReel');
                    $montantParMoisReelInt = intval($montantParMoisReel);
                    $id_usercontrat = Session::get('id_usercontrat');

                    $anneeActuelle = date('Y');

                    $infoParamContrat = Paramcontrat::first();
                    $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
                    $anneeSuivante = $debutAnneeEnCours + 1;
                    $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;

                    // Array des noms des mois
                    $nomsMoisCoches = [];
                    if (is_array($moisCoches)) {

                        // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                        foreach ($moisCoches as $id_moiscontrat) {
                            // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                            $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                            
                            // Vérifiez si le mois existe
                            if ($mois) {
                                // Ajouter le nom du mois à l'array
                                $nomsMoisCoches[] = $mois->nom_moiscontrat;
                            }
                        }
                    }

                    // Convertir le tableau en une chaîne de caractères
                    $moisConcatenes = implode(',', $nomsMoisCoches);


                    $parametrefacture = Params2::first();
                    $ifuentreprise = $parametrefacture->ifu;
                    $tokenentreprise = $parametrefacture->token;
                    $taxe = $parametrefacture->taxe;
                    $type = $parametrefacture->typefacture;
                
                    $parametreetab = Params2::first();

                    $moisavecvirg = implode(',', $nomsMoisCoches);
                    $toutmoiscontrat = $moisavecvirg;

                    $items = []; // Initialiser un tableau vide pour les éléments

                    foreach ($nomsMoisCoches as $idmois => $mois) {
                        $items[] = [
                            'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                            'price' => intval($montantmoiscontrat),
                            'quantity' => 1,
                            'taxGroup' => $taxe,
                        ];

                        // Définir $montantAPayer par défaut pour tous les mois
                        if (in_array($mois, ['Decembre', 'Septembre', 'Avril'])) {
                            // Montant spécifique pour certains mois
                            switch ($mois) {
                                case 'Decembre':
                                    $montantAPayer = 17000;
                                    break;
                                case 'Septembre':
                                    $montantAPayer = 17000;
                                    break;
                                case 'Avril':
                                    $montantAPayer = 17000;
                                    break;
                            }
                        } else {
                            // Montant par défaut pour les autres mois
                            $montantAPayer = 17000;
                        }
                        
                        // Requête pour récupérer les informations de facture
                        $infoFacture = Facturenormalise::where('idcontrat', $idcontratEleve)
                            ->where('moispayes', $mois)
                            ->get();
                        // Calculer le total des montants
                        $totalMontantinfoFacture = $infoFacture->sum('montant_par_mois');

                        // Si $totalMontantinfoFacture est null, le remplacer par 0
                        $totalMontantinfoFacture = $totalMontantinfoFacture ?? 0;

                        // Calculer la somme des montants
                        $sommeDesMontant = $totalMontantinfoFacture + $montantmoiscontrat;

                        // Déterminer si le mois peut être sauvegardé
                        if ($sommeDesMontant < $montantAPayer) {
                            $saveMois = 1;                      
                        } else {
                            $saveMois = 0;
                        }

                    }

                    $jsonData = json_encode([
                        "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                        // "aib" => "A",
                        "type" => $type,
                        "items" => $items,

                        "client" => [
                            // "ifu" => '',
                            "name"=>  $nomcompleteleve,

                        ],
                        "operator" => [
                            "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                        ],
                        "payment" => [
                            [
                            "name" => "ESPECES",
                              "amount" => intval($montanttotal)
                            ]
                          ],
                    ]);

                    // Définissez l'URL de l'API de facturation
                    $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
                    
            
                    // Définissez le jeton d'authentification

                        $token = $tokenentreprise;
                    //    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjEyMDEyMDE1NzQ1MDJ8VFMwMTAxMjE5OCIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyOTAwMTYwNiwiZXhwIjoxOTI0OTAyMDAwLCJpYXQiOjE3MjkwMDE2MDYsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.t0VBBtOig972FWCW2aFk7jyb-SHKv1iSZ9bkM-IGc2M";
                        // $token = $tokenentreprise;
            
                    // Effectuez la requête POST à l'API
                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ));
                    curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                    // Exécutez la requête cURL et récupérez la réponse
                    $response = curl_exec($ch);
                    // dd($response);
                    
                    // Vérifiez les erreurs de cURL
                    if (curl_errno($ch)) {
                        // echo 'Erreur cURL : ' . curl_error($ch);
                        return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
                    }
                    
                    // Fermez la session cURL
                    curl_close($ch);
                    
                    // Affichez la réponse de l'API
                    $decodedResponse = json_decode($response, true);
                    // dd($decodedResponse);
                    
                    // Vérifiez si l'UID est présent dans la réponse
                    if (isset($decodedResponse['uid'])) {
                        // L'UID de la demande
                        $uid = $decodedResponse['uid'];

                        // -------------------------------
                            //  RECUPERATION DE LA FACTURE PAR SON UID
                        // -------------------------------

                        // Définissez l'URL de l'API de confirmation de facture
                        $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
                
                        // Configuration de la requête cURL pour la confirmation
                        $chRecuperation = curl_init($recuperationUrl);
                        curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                        curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                            'Content-Length: 0'
                        ]);
                        curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

                        // Exécutez la requête cURL pour la confirmation
                        $responseRecuperation = curl_exec($chRecuperation);
                        // dd($responseRecuperation);
                        // Vérifiez les erreurs de cURL pour la confirmation


                        // Fermez la session cURL pour la confirmation
                        curl_close($chRecuperation);

                        // Convertissez la réponse JSON en tableau associatif PHP
                        $decodedDonneFacture = json_decode($responseRecuperation, true);
                        // dd($decodedDonneFacture);

                        $facturedetaille = json_decode($jsonData, true);
                        $ifuEcoleFacture = $decodedDonneFacture['ifu'];
                        $itemFacture = $decodedDonneFacture['items'];
                        $jsonItem = json_encode($itemFacture);
                        $doneeDetailleItemFacture = $itemFacture['0'];
                        $nameItemFacture = $doneeDetailleItemFacture['name'];
                        $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
                        $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
                        $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
                        // $idd = $responseRecuperation.ifu;
                        $nameClient = $decodedDonneFacture['client']['name'];
                        // dd($prixTotalItemFacture);

                    
                        // -------------------------------
                            //  CONFIRMATION DE LA FACTURE 
                        // -------------------------------

                        // ACTION pour la confirmation
                        $actionConfirmation = 'confirm';
                    
                        // Définissez l'URL de l'API de confirmation de facture
                        $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
                    
                        // Configuration de la requête cURL pour la confirmation
                        $chConfirmation = curl_init($confirmationUrl);
                        curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                            'Content-Length: 0'
                        ]);
                        curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
                    
                        // Exécutez la requête cURL pour la confirmation
                        $responseConfirmation = curl_exec($chConfirmation);
                    
                        // Vérifiez les erreurs de cURL pour la confirmation
                        if (curl_errno($chConfirmation)) {
                            // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
                            return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

                        }
    
                        // Fermez la session cURL pour la confirmation
                        curl_close($chConfirmation);
                    
                        // Convertissez la réponse JSON en tableau associatif PHP
                        $decodedResponseConfirmation = json_decode($responseConfirmation, true);
                        // dd($decodedResponseConfirmation);


                        $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

                        $counters = $decodedResponseConfirmation['counters'];
                
                        $nim = $decodedResponseConfirmation['nim'];
                
                        $dateTime = $decodedResponseConfirmation['dateTime'];
                
                        // Générer le code QR
                        $qrCodeString = $decodedResponseConfirmation['qrCode'];
                
                        $reffactures = $nim.'-'.$counters;
                
                        $reffacture = explode('/', $reffactures)[0];

                        // gestion du code qr sous forme d'image

                        $fileNameqrcode = $nomcompleteleve . time() . '.png';
                        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->data($qrCodeString)
                            ->size(100)
                            // ->margin(10)
                            ->build();

                        $qrcodecontent = $result->getString();

                        do {
                            // Génère un nombre aléatoire entre 10000000 et 99999999
                        $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
                        } while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());
    
                        // dd($moisCoches);  
    
                        // ENREGISTREMENT DANS LA TABLE INSCRIPTIONCONTRAT
                         // Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
                         foreach ($moisCoches as $id_moiscontrat) {
                            if ($saveMois == 0) {
                                Inscriptioncontrat::create([
                                     // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                                     'id_contrat' => $idcontratEleve,
                                     'id_moiscontrat' => $id_moiscontrat,
                                     'anne_inscription' => $debutAnneeEnCours,
                                    
                                 ]);
                            }else{
                                // 
                            }
                         }
    
                         // recuperer les nom des mois cochee
    
                        // Array des noms des mois
                        $nomsMoisCoches = [];
    
                        // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                        foreach ($moisCoches as $id_moiscontrat) {
                            // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                            $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                            
                            // Vérifiez si le mois existe
                            if ($mois) {
                                // Ajouter le nom du mois à l'array
                                $nomsMoisCoches[] = $mois->nom_moiscontrat;
                            }
                        }

                        // Convertir le tableau en une chaîne de caractères
                        $moisConcatenes = implode(',', $nomsMoisCoches);
                        // dd($moisConcatenes);
                        // Récupérer la somme des montants de paiement précédents
                        $soldeavant_paiementcontrat = DB::table('paiementglobalcontrat')
                        ->where('id_contrat', $idcontratEleve)
                        ->sum('montant_paiementcontrat');


                        $InfoUtilisateurConnecter =  User::where('id', $id_usercontrat)->first();
                        $idUserCont =  $InfoUtilisateurConnecter->id;
                        $idUserContInt = intval($idUserCont);

                        // dd($soldeavant_paiementcontrat);
                        // Calculer le solde après le paiement en ajoutant le montant du paiement actuel à la somme des montants précédents
                        $soldeapres_paiementcontrat = $soldeavant_paiementcontrat + $montantmoiscontrat;
                        // dd($soldeapres_paiementcontrat);

                        // ENREGISTREMENT DANS LA TABLE PAIEMENTGLOBALCONTRAT
                        $paiementglobalcontrat =  new Paiementglobalcontrat();
                            
                        $paiementglobalcontrat->soldeavant_paiementcontrat = $soldeavant_paiementcontrat;
                        $paiementglobalcontrat->montant_paiementcontrat = $montanttotal;
                        $paiementglobalcontrat->soldeapres_paiementcontrat = $soldeapres_paiementcontrat;
                        $paiementglobalcontrat->id_contrat = $idcontratEleve;
                        $paiementglobalcontrat->date_paiementcontrat = $datepaiementcontrat;
                            $paiementglobalcontrat->id_usercontrat = $idUserContInt;
                        $paiementglobalcontrat->anne_paiementcontrat = $debutAnneeEnCours;
                        $paiementglobalcontrat->reference_paiementcontrat = $valeurDynamiqueNumerique;
                        $paiementglobalcontrat->statut_paiementcontrat = 1;
                        //     $paiementglobalcontrat->datesuppr_paiementcontrat = null;
                        //    $paiementglobalcontrat->idsuppr_usercontrat = null;
                        //    $paiementglobalcontrat->motifsuppr_paiementcontrat = null;
                        $paiementglobalcontrat->mois_paiementcontrat = $moisConcatenes;

                        $paiementglobalcontrat->save();

                        // Récupérer l'id_paiementcontrat de la table paiementglobalcontrat qui correspond a l'id du contrat
                        $idPaiementContrat = Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
                        ->orderBy('id_paiementcontrat', 'desc')
                        ->value('id_paiementcontrat');
                        // dd($idPaiementContrat);                

                        // ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT

                        // dd($soldeavant_paiementcontrat);
                        // Créer un objet DateTime à partir de la chaîne de caractères
                        $datezz = new DateTime($datepaiementcontrat);

                        // Formater la date sans l'heure
                        $datezzSansHeure = $datezz->format('Y-m-d');  // Cela donnera "2025-02-18"

                        // Parcourir les mois cochés et insérer chaque id de mois dans la table Paiementcontrat
                        foreach ($moisCoches as $id_moiscontrat) {
                            Paiementcontrat::create([
                                // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                                'soldeavant_paiementcontrat' => $soldeavant_paiementcontrat,
                                'montant_paiementcontrat' => $montantmoiscontrat,
                                'soldeapres_paiementcontrat' => $soldeapres_paiementcontrat,
                                'id_contrat' => $idcontratEleve,
                                'date_paiementcontrat' => $datepaiementcontrat,
                                // 'date_paiementcontrat' => $datezzSansHeure,
                                'id_usercontrat' => $idUserContInt,
                                'mois_paiementcontrat' => $id_moiscontrat,
                                'anne_paiementcontrat' => $debutAnneeEnCours,
                                'reference_paiementcontrat' => $valeurDynamiqueNumerique,
                                'statut_paiementcontrat' => 1,
                                // 'datesuppr_paiementcontrat' => $anneeActuelle,
                                // 'idsuppr_usercontrat' => $anneeActuelle,
                                // 'motifsuppr_paiementcontrat' => $anneeActuelle,
                                'id_paiementglobalcontrat' => $idPaiementContrat,
                                'montanttotal' => $montanttotal
                            ]);
                        }

                        // CALCUL DU TOTALHT ET TOTALTVA

                        $TOTALHT = $montanttotal / 1.18;
                        $totalHTArrondi = 0;
                        $TOTALTVA = 0;

                        // dd($ifuEcoleFacture);
                        $facturenormalise = new Facturenormalise();
                        $facturenormalise->id = $reffacture;
                        $facturenormalise->codemecef = $codemecef;
                        $facturenormalise->counters = $counters;
                        $facturenormalise->nim = $nim;
                        $facturenormalise->dateHeure = $dateTime;
                        $facturenormalise->ifuEcole = $ifuEcoleFacture;
                        $facturenormalise->MATRICULE = $matriculeeleve;
                        $facturenormalise->idcontrat = $idcontratEleve;
                        $facturenormalise->moispayes = $moisConcatenes;
                        $facturenormalise->classe = $classeeleve;
                        $facturenormalise->nom = $nameClient;
                        $facturenormalise->itemfacture = $jsonItem;
                        $facturenormalise->designation = 'Frais cantine pour: '.$moisConcatenes;
                        $facturenormalise->montant_total = $montanttotal;
                        $facturenormalise->TOTALHT = $totalHTArrondi;
                        $facturenormalise->TOTALTVA = $TOTALTVA;
                        $facturenormalise->montant_par_mois = $montantmoiscontrat;
                        $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
                        $facturenormalise->qrcode = $qrcodecontent;
                        $facturenormalise->statut = 1;
                        $facturenormalise->typefac = $typefac;
                        // $facturenormalise->type = "FC";
            
                        $facturenormalise->save();

                        $paramse = Params2::first(); 

                        $logoUrl = $paramse ? $paramse->logoimage: null; 
                    
                        $NOMETAB = $paramse->NOMETAB;

                        Session::put('factureconfirm', $decodedResponseConfirmation);
                        Session::put('fileNameqrcode', $fileNameqrcode);
                        Session::put('facturedetaille', $facturedetaille);
                        Session::put('reffacture', $reffacture);
                        Session::put('classeeleve', $classeeleve);
                        Session::put('nomcompleteleve', $nomcompleteleve);
                        Session::put('toutmoiscontrat', $toutmoiscontrat);
                        Session::put('nameItemFacture', $nameItemFacture);
                        Session::put('prixTotalItemFacture', $prixTotalItemFacture);
                        Session::put('quantityItemFacture', $quantityItemFacture);
                        Session::put('taxGroupItemFacture', $taxGroupItemFacture);
                        Session::put('ifuEcoleFacture', $ifuEcoleFacture);
                        Session::put('qrCodeString', $qrCodeString);
                        Session::put('itemFacture', $itemFacture);
                        Session::put('montanttotal', $montanttotal);
                        Session::put('totalHTArrondi', $totalHTArrondi);
                        Session::put('TOTALTVA', $TOTALTVA);
                        Session::put('montantmoiscontrat', $montantmoiscontrat);
                        Session::put('qrcodecontent', $qrcodecontent);
                        Session::put('NOMETAB', $NOMETAB);
                        Session::put('nim', $nim);
                        Session::put('datepaiementcontrat', $datepaiementcontrat);
                        Session::put('dateTime', $dateTime);

                        return view('pages.Etats.pdffacture', [
                            'factureconfirm' => $decodedResponseConfirmation,
                            'fileNameqrcode' => $fileNameqrcode,
                            'facturedetaille' => $facturedetaille,
                            'reffacture' => $reffacture,
                            'ifuEcoleFacture' => $ifuEcoleFacture,
                            'nameItemFacture' => $nameItemFacture,
                            'prixTotalItemFacture' => $prixTotalItemFacture,
                            'quantityItemFacture' => $quantityItemFacture,
                            'taxGroupItemFacture' => $taxGroupItemFacture,
                            'classeeleve' => $classeeleve,
                            'nomcompleteleve' => $nomcompleteleve,
                            'toutmoiscontrat' => $toutmoiscontrat,
                            'qrCodeString' => $qrCodeString,
                            'logoUrl' => $logoUrl,
                            'itemFacture' => $itemFacture,
                            'montanttotal' => $montanttotal,
                            'qrcodecontent' => $qrcodecontent,
                            'NOMETAB' => $NOMETAB,
                            'nim' => $nim,
                            'datepaiementcontrat' => $datepaiementcontrat,
                            'dateTime' => $dateTime,
                            'totalHTArrondi' => $totalHTArrondi,
                            'TOTALTVA' => $TOTALTVA,                    
                        ]);

                    }


                    // FIN DE LA CREATION DE LA FACTURE

        }
    
        // return back()->with('status', "Facture d'avoir generer avec succes");
    
        
            }
        } else {
            // dd('codemecef incorrect');
            return back()->with('erreur', "Le codemecef entrer ne correspond pas a celui de la facture originale.");

        }

       
        
    }




    public function avoirfactureinscription($codemecef){

        // dd('code correct');
        $codemecefEntrer = $request->input('inputCodemecef');
        if ($codemecefEntrer == $codemecef) {
            // dd('codemecef correct');
        $infoparam = Params2::first();
        $tokenentreprise = $infoparam->token;

        $factureoriginale = DB::table('facturenormaliseinscription')->where('codemecef', $codemecef)->first();
        $ifuentreprise = $factureoriginale->ifuEcole;
        $montanttotal = $factureoriginale->montant_total;
        $TOTALTVA = $factureoriginale->TOTALTVA;
        $TOTALHT = $factureoriginale->TOTALHT;

        $nomcompleteleve = $factureoriginale->nom;
        // $moisConcatenes = $factureoriginale->moispayes;
        $matriculeeleve = $factureoriginale->MATRICULE;
        // $idcontratEleve = $factureoriginale->idcontrat;
        $classeeleve = $factureoriginale->classe;
        // $montantparmois = $factureoriginale->montant_par_mois;
        $datepaiementcontrat = $factureoriginale->datepaiementcontrat;
        // dd($moisConcatenes);
                    // -------------------------------
                        //  CREATION DE LA FACTURE
                    // -------------------------------
                        // dd($moisConcatenes);
                        // $moisArray = explode(',', $moisConcatenes);
                        // dd($moisArray);

                        // $items = []; // Initialiser un tableau vide pour les éléments

                        // foreach ($moisArray as $idmois => $mois) {
                        //     $items[] = [
                        //         'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                        //         'price' => intval($montantparmois),
                        //         'quantity' => 1,
                        //         'taxGroup' => 'B',
                        //     ];
                        // }

                        // Préparez les données JSON pour l'API
                        $jsonData = json_encode([
                            "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                            // "aib" => "A",
                            "type" => 'FA',
                            "items" => [
                                [
                                    'name' => 'Inscription cantine', // Pas besoin de $$ pour une variable
                                    'price' => intval($montanttotal),
                                    'quantity' => 1,
                                    'taxGroup' => 'E',
                                ]
                            ],
                            "client" => [
                                // "ifu" => '',
                                "name"=>  $nomcompleteleve,
                                // "contact" => "string",
                                // "address"=> "string"
                            ],
                            "operator" => [
                                "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                            ],
                            "payment" => [
                                [
                                "name" => "ESPECES",
                                  "amount" => intval($montanttotal), 
                                ]
                              ],
                              "reference" => $codemecef,
                        ]);
                    // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


                    //  dd($jsonData);

                    // Définissez l'URL de l'API de facturation
                    $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';

                    // Définissez le jeton d'authentification
                    // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODU5MTExMzh8VFMwMTAxMTQ3MiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyNDI1NzQyMywiZXhwIjoxNzM3NDE0MDAwLCJpYXQiOjE3MjQyNTc0MjMsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.sRcSeEbIuQNSgFebRRaxW4zPLCqlF6PQXc90e2xfHCs';
                    $token = $tokenentreprise;

                    // Effectuez la requête POST à l'API
                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ));
                    curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

                // Exécutez la requête cURL et récupérez la réponse
        $response = curl_exec($ch);
        // dd($response);

        $decodedResponse = json_decode($response, true);

            // Vérifiez si l'UID est présent dans la réponse
            if (isset($decodedResponse['uid'])) {
                // L'UID de la demande
                $uid = $decodedResponse['uid'];
                // $taxb = 0.18;

                                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

                // Définissez l'URL de l'API de confirmation de facture
                $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
                // Configuration de la requête cURL pour la confirmation
                $chRecuperation = curl_init($recuperationUrl);
                curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                // Exécutez la requête cURL pour la confirmation
                $responseRecuperation = curl_exec($chRecuperation);
                // dd($responseRecuperation);
                // Vérifiez les erreurs de cURL pour la confirmation

    
                // Fermez la session cURL pour la confirmation
                curl_close($chRecuperation);
    
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedDonneFacture = json_decode($responseRecuperation, true);
    
            // $facturedetaille = json_decode($jsonData, true);
            $ifuEcoleFacture = $decodedDonneFacture['ifu'];
            // dd($ifuEcoleFacture);
            $itemFacture = $decodedDonneFacture['items'];
            $jsonItems = json_encode($itemFacture);
            $doneeDetailleItemFacture = $itemFacture['0'];
            $nameItemFacture = $doneeDetailleItemFacture['name'];
            $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
            $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
            $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
            // $idd = $responseRecuperation.ifu;
            $nameClient = $decodedDonneFacture['client']['name'];
            // dd($itemFacture);

             // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

                 // ACTION pour la confirmation
                 $actionConfirmation = 'confirm';

                // Définissez l'URL de l'API de confirmation de facture
                $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
            
                // Configuration de la requête cURL pour la confirmation
                $chConfirmation = curl_init($confirmationUrl);
                curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
            
                // Exécutez la requête cURL pour la confirmation
                $responseConfirmation = curl_exec($chConfirmation);
            
            
                // Fermez la session cURL pour la confirmation
                curl_close($chConfirmation);
            
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedResponseConfirmation = json_decode($responseConfirmation, true);
            // dd($decodedResponseConfirmation);

    
                $codemecefavoir = $decodedResponseConfirmation['codeMECeFDGI'];
    
                $counters = $decodedResponseConfirmation['counters'];
    
                $nim = $decodedResponseConfirmation['nim'];
    
                $dateTime = $decodedResponseConfirmation['dateTime'];
    
                      // Générer le code QR
                      $qrCodeString = $decodedResponseConfirmation['qrCode'];
    
                      $reffactures = $nim.'-'.$counters;

                      $reffacture = explode('/', $reffactures)[0];

                      
          
                      // dd($reffacture);
      
                  // gestion du code qr sous forme d'image
          
                  // $fileNameqrcode = $nomcompleteleve . time() . '.png';
                  $result = Builder::create()
                      ->writer(new PngWriter())
                      ->data($qrCodeString)
                      ->size(100)
                      // ->margin(10)
                      ->build();
          
                      $qrcodecontent = $result->getString();

                    //   dd($qrcodecontent);
          
            // ENREGISTREMENT DE LA FACTURE
            //  $facturenormalise = new Facturenormalise();
            //  $facturenormalise->id = $reffacture;
            //  $facturenormalise->codemecef = $codemecefavoir;
            //  $facturenormalise->codemeceffacoriginale = $codemecef;
            //  $facturenormalise->counters = $counters;
            //  $facturenormalise->nim = $nim;
            //  $facturenormalise->dateHeure = $dateTime;
            //  $facturenormalise->ifuEcole = $ifuEcoleFacture;
            //  $facturenormalise->MATRICULE = $matriculeeleve;
            //  $facturenormalise->idcontrat = $idcontratEleve;
            //  $facturenormalise->moispayes = $moisConcatenes;
            //  $facturenormalise->itemfacture = $jsonItems;
            //  $facturenormalise->classe = $classeeleve;
            //  $facturenormalise->nom = $nameClient;
            //  $facturenormalise->designation = $nameItemFacture;
            //  $facturenormalise->montant_total = $montanttotal;
            //  $facturenormalise->TOTALTVA = $TOTALTVA;
            //  $facturenormalise->TOTALHT = $TOTALHT;
            //  $facturenormalise->montant_par_mois = intval($montantparmois);
            // //  $facturenormalise->montant_total = $prixTotalItemFacture;
            //  $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
            //  $facturenormalise->qrcode = $qrcodecontent;
            //  $facturenormalise->statut = 0;
        
            //  $facturenormalise->save();

                                 // ENREGISTREMENT DE LA FACTURE
                                 $facturenormaliseinscription = new Facturenormaliseinscription();
                                 $facturenormaliseinscription->id = $reffacture;
                                 $facturenormaliseinscription->codemecef = $codemecefavoir;
                                 $facturenormaliseinscription->codemeceffacoriginale = $codemecef;
                                 $facturenormaliseinscription->counters = $counters;
                                 $facturenormaliseinscription->nim = $nim;
                                 $facturenormaliseinscription->dateHeure = $dateTime;
                                 $facturenormaliseinscription->ifuEcole = $ifuEcoleFacture;
                                 $facturenormaliseinscription->MATRICULE = $matriculeeleve;
                                 // $facturenormalise->idcontrat = $idcontratEleve;
                                 // $facturenormalise->moispayes = $moisConcatenes;
                                 $facturenormaliseinscription->TOTALHT = $TOTALHT;
                                 $facturenormaliseinscription->TOTALTVA = $TOTALTVA;
                                 $facturenormaliseinscription->classe = $classeeleve;
                                 $facturenormaliseinscription->nom = $nameClient;
                                 $facturenormaliseinscription->designation = $nameItemFacture;
                                 $facturenormaliseinscription->montant_total = $montanttotal;
                                 $facturenormaliseinscription->datepaiementcontrat = $datepaiementcontrat;
                                 $facturenormaliseinscription->qrcode = $qrcodecontent;
                                 $facturenormaliseinscription->statut = 0;
                             
                                 $facturenormaliseinscription->save();

                                 DB::table('facturenormaliseinscription')
                                 ->where('codemecef', $codemecef)
                                 ->update(['statut' => 0]);

        // effacer les donnes de la facture qui sont dans paiementcontrat et paiementglobalcontrat

            // MISE À JOUR DES LIGNES DANS paiementcontrat
            Paiementcontrat::where('id_contrat', $idcontratEleve)
            ->where('date_paiementcontrat', $datepaiementcontrat)
            ->update(['statut_paiementcontrat' => 0]);

            // MISE À JOUR DES LIGNES DANS paiementglobalcontrat
            // Paiementglobalcontrat::where('id_contrat', $idcontratEleve)
            //     ->where('date_paiementcontrat', $datepaiementcontrat)
            //     ->update(['statut_paiementcontrat' => 0]);


            return back()->with('status', "Facture d'avoir generer avec succes");

    
            }

        } else {
            // dd('codemecef incorrect');
            return back()->with('erreur', "Le codemecef entrer ne correspond pas a celui de la facture originale.");

        }
            
    }


public function factures() {
    $paramse = Params2::first(); 

    $logoUrl = $paramse ? $paramse->logoimage: null; 

    $NOMETAB = $paramse->NOMETAB;
    $factures = DB::table('facturenormalises')->get();

    // dd($facturesParEleve);

    // $facturesParEleve = DB::table('facturenormalises')
    // ->orderBy('MATRICULE')
    // ->get()
    // ->groupBy('MATRICULE');
    // dd($facturesParEleve);
    // $jsonItem = $facturePaie->itemfacture;
    // $donneItem = json_decode($jsonItem);

    return view('pages.Etats.fact', compact('factures', 'logoUrl', 'NOMETAB'));

}

public function show($id)
{
    $paramse = Params2::first(); 
    // dd($id);
    $logoUrl = $paramse ? $paramse->logoimage: null;
    $NOMETAB = $paramse->NOMETAB;
 
    // Récupérer toutes les factures pour un élève spécifique
    $facture = DB::table('facturenormalises')->where('id', $id)->first();
    // dd($factures->id);

    // Passer les données à la vue
    return view('pages.Etats.show', compact('facture', 'logoUrl', 'NOMETAB'));
}
        // fin facture normalisee pour tous les paiements de l'annee 2023_2024



    public function telechargerfacture() {
        $decodedResponseConfirmation = Session::get('factureconfirm');
        $facturedetaille = Session::get('facturedetaille');
        $reffacture = Session::get('reffacture');
        $classeeleve = Session::get('classeeleve');
        $nomcompleteleve = Session::get('nomcompleteleve');
        $toutmoiscontrat = Session::get('toutmoiscontrat');
        $qrCodeString = Session::get('qrCodeString');
        $qrcodecontent = Session::get('qrcodecontent');
        $fileNameqrcode = Session::get('fileNameqrcode');
        // $reffacture = Session::get('reffacture');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.facture', [
                'factureconfirm' => $decodedResponseConfirmation,
                'fileNameqrcode' => $fileNameqrcode,
                'facturedetaille' => $facturedetaille, 
                'reffacture' => $reffacture,
                'classeeleve' => $classeeleve,
                'nomcompleteleve' => $nomcompleteleve,
                'toutmoiscontrat' => $toutmoiscontrat,
                'qrCodeString' => $qrCodeString,
                'qrcodecontent' => $qrcodecontent,
            ]);        
            return $pdf->download('facture.pdf');
        // return view('Essai.mercccii');
    }

    public function facturenormalise(Request $request) {
        $decodedResponseConfirmation = Session::get('factureconfirm');
        $facturedetaille = Session::get('facturedetaille');
        $reffacture = Session::get('reffacture');
        $classeeleve = Session::get('classeeleve');
        $nomcompleteleve = Session::get('nomcompleteleve');
        $toutmoiscontrat = Session::get('toutmoiscontrat');
        $nameItemFacture = Session::get('nameItemFacture');
        $prixTotalItemFacture = Session::get('prixTotalItemFacture');
        $quantityItemFacture = Session::get('quantityItemFacture');
        $taxGroupItemFacture = Session::get('taxGroupItemFacture');
        $ifuEcoleFacture = Session::get('ifuEcoleFacture');
        $itemFacture = Session::get('itemFacture');
                $montanttotal = Session::get('montanttotal');
                $totalHTArrondi = Session::get('totalHTArrondi');
                $TOTALTVA = Session::get('TOTALTVA');
                $qrCodeString = Session::get('qrCodeString');
                $fileNameqrcode = Session::get('fileNameqrcode');
                $qrcodecontent = Session::get('qrcodecontent');
                $fileNameqrcode = Session::get('fileNameqrcode');
                $nim = Session::get('nim');
                $datepaiementcontrat = Session::get('datepaiementcontrat');
                $dateTime = Session::get('dateTime');

                $paramse = Params2::first(); 

                $logoUrl = $paramse ? $paramse->logoimage: null;
                $NOMETAB = $paramse->NOMETAB; 

                        $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';
                // dd($NOMETAB);
                // $villeetab = Session::get('villeetab');
                // $nometab = Session::get('nometab');


                    // // Générer le PDF

                    // $data = [
                    //     'decodedResponseConfirmation' => $decodedResponseConfirmation,
                    //     'facturedetaille' => $facturedetaille,
                    //     'reffacture' => $reffacture,
                    //     'classeeleve' => $classeeleve,
                    //     'nomcompleteleve' => $nomcompleteleve,
                    //     'toutmoiscontrat' => $toutmoiscontrat,
                    //     'qrCodeString' => $qrCodeString,
                    //     'fileNameqrcode' => $fileNameqrcode,
                    //     'logoUrl' => $logoUrl,
                    //     'qrcodecontent' => $qrcodecontent,
                    // ];

                    // $datepaiement = $decodedResponseConfirmation['dateTime'];
                    // // dd($datepaiement);
                
                    // // Spécifiez le nom du fichier avec un timestamp pour garantir l'unicité
                    // $fileName = $nomcompleteleve . time() . '.pdf';
                
                    // // Spécifiez le chemin complet vers le sous-dossier pdfs dans public
                    // $filePaths = public_path('pdfs/' . $fileName);
                
                    // // Assurez-vous que le répertoire pdfs existe, sinon créez-le
                    // if (!file_exists(public_path('pdfs'))) {
                    //     mkdir(public_path('pdfs'), 0755, true);
                    // }
                
                    // Générer et enregistrer le PDF dans le sous-dossier pdfs
                    // $pdf = PDF::loadView('pages.Etats.essaipdf', $data);
                    // $pdfcontent = $pdf->output();
                
                
                    //    // Enregistrer le chemin du PDF dans la base de données
                    //                 $duplicatafacture = new Duplicatafacture();
                    //                 $duplicatafacture->url = $pdfcontent;
                    //                 $duplicatafacture->nomeleve = $nomcompleteleve;
                    //                 $duplicatafacture->classe = $classeeleve;
                    //                 $duplicatafacture->reference = 'Facture de paiement';
                    //                 $duplicatafacture->datepaiement = $datepaiement;
                    //                 $duplicatafacture->save();


        // dd($fileName);
        return view('pages.Etats.facturenormalise',  [
            'factureconfirm' => $decodedResponseConfirmation,
            'facturedetaille' => $facturedetaille, 
            'reffacture' => $reffacture,
            'classeeleve' => $classeeleve,
            'nomcompleteleve' => $nomcompleteleve,
            'toutmoiscontrat' => $toutmoiscontrat,
            'prixTotalItemFacture' => $prixTotalItemFacture,
            'quantityItemFacture' => $quantityItemFacture,
            'nameItemFacture' => $nameItemFacture,
            'taxGroupItemFacture' => $taxGroupItemFacture,
            'ifuEcoleFacture' => $ifuEcoleFacture,
            'qrCodeString' => $qrCodeString,
            'logoUrl' => $logoUrl,
            'NOMETAB' => $NOMETAB,
            'fileNameqrcode' => $fileNameqrcode,
            'qrcodecontent' => $qrcodecontent,
            'nim' => $nim,
            'datepaiementcontrat' => $datepaiementcontrat,
            'dateTime' => $dateTime,
            'itemFacture' => $itemFacture,
            'montanttotal' => $montanttotal,
            'TOTALTVA' => $TOTALTVA,
            'entete' => $entete,
            'totalHTArrondi' => $totalHTArrondi,

            // 'nometab' => $nometab,
            // 'villeetab' => $villeetab,
        ]);        
    }


    public function duplicatainscription() {
        $amount = Session::get('amount');
        $classe = Session::get('classe');
        $logoUrl = Session::get('logoUrl');
        $dateContrat = Session::get('dateContrat');
        $elevyo = Session::get('elevyo');
        $nometab = Session::get('nometab');
        $ifu = Session::get('ifu');

        // dd($nometab);


        $data = [
            'amount' => $amount,
            'classe' => $classe,
            'logoUrl' => $logoUrl,
            'dateContrat' => $dateContrat,
            'elevyo' => $elevyo,
        ];

       
    
                // Spécifiez le nom du fichier avec un timestamp pour garantir l'unicité
                $fileName = $elevyo . time() . '.pdf';
            
                // Spécifiez le chemin complet vers le sous-dossier pdfs dans public
                $filePaths = public_path('pdfs/' . $fileName);
            
                // Assurez-vous que le répertoire pdfs existe, sinon créez-le
                if (!file_exists(public_path('pdfs'))) {
                    mkdir(public_path('pdfs'), 0755, true);
                }

                        // Générer et enregistrer le PDF dans la base de donne
                        // $pdfdupinscri = PDF::loadView('pages.Etats.doubleinscriptionpdf', $data);
                        // $pdfcontentdupinscri = $pdfdupinscri->output();
            
                // Générer et enregistrer le PDF dans le sous-dossier pdfs
            // $pdf = PDF::loadView('pages.Etats.doubleinscriptionpdf', $data)->save($filePaths);
            
    
           // Enregistrer le chemin du PDF dans la base de données
                        // $duplicatafacture = new Duplicatafacture();
                        // $duplicatafacture->url = $pdfcontentdupinscri;
                        // $duplicatafacture->nomeleve = $elevyo;
                        // $duplicatafacture->classe = $classe;
                        // $duplicatafacture->reference = 'Facture d\'inscription';

                        // $duplicatafacture->datepaiement = $dateContrat;
                        // $duplicatafacture->save();
                        return view('pages.Etats.duplicatainscription', [
                            'amount' => $amount,
                            'classe' => $classe,
                            'logoUrl' => $logoUrl,
                            'dateContrat' => $dateContrat,
                            'elevyo' => $elevyo,
                            'nometab' => $nometab,
                            'ifu' => $ifu,
                
                        ]);  
        
    }


    public function doubleinscriptionpdf() {

        $amount = Session::get('amount');
        $classe = Session::get('classe');
        $dateContrat = Session::get('dateContrat');
        $elevyo = Session::get('elevyo');
    
        $paramse = Paramsfacture::first(); 
    
        $logoUrl = $paramse ? $paramse->logo: null; 
    }

    public function essaipdf() {

        $decodedResponseConfirmation = Session::get('factureconfirm');
        $facturedetaille = Session::get('facturedetaille');
        $reffacture = Session::get('reffacture');
        $classeeleve = Session::get('classeeleve');
        $nomcompleteleve = Session::get('nomcompleteleve');
        $toutmoiscontrat = Session::get('toutmoiscontrat');
        $qrCodeString = Session::get('qrCodeString');

        $paramse = Paramsfacture::first(); 

        $logoUrl = $paramse ? $paramse->logo: null; 
    }


        public function etat() {
            $annees = Paramcontrat::distinct()->pluck('anneencours_paramcontrat'); 
            $classes = Classes::get();
            return view('pages.etat')->with('annee', $annees)->with('classe', $classes);
        }

















































































































   

    // public function traiter(Request $request)
    // {
    //     // Récupérer l'ID de l'élève à partir de la requête
    //     $eleveNom = $request->input('eleveNom');
    //     dd($eleveNom);

    //     // Utiliser l'ID de l'élève pour effectuer des opérations dans votre contrôleur
    //     // Par exemple, charger les détails de l'élève à partir de la base de données
        
    //     // Retourner une réponse (si nécessaire)
    //     return response()->json(['message' => 'Informations de l\'élève traitées avec succès.']);
    // }
    // public function getElevesByClasse($CODECLAS) {
    //     $elevess = Eleve::where('CODECLAS', $CODECLAS)->get();
        
    //     // dd($elevess);// Récupérer les élèves de la classe sélectionnée en fonction de $codeClasse
    //     return view('pages.partial')->with('elevess', $elevess);
    //   }
 
    // public function creercontrat(Request $request){
    //     $matricules = $request->input('matricules');
    //     $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
    //     if($existingContrat) {
    //         return back()->with('status', 'Un contrat existe déjà pour cet élève.');
    //     }
    //     $contra = new Contrat();
    //     $contra->eleve_contrat = $matricules;
    //     $contra->cout_contrat = $request->input('montant');

    //     $contra->datecreation_contrat = $request->input('date');
    //     $contra->save();
    
    //     return back()->with('status','Contrat enregistré avec succès');
    // }
    public function verifyContrat(Request $request){
        $eleveId = $request->input('eleve_contrat');

        // Vérification si un contrat existe déjà pour cet élève
        $contratExistant = Contrat::where('eleve_contrat', $eleveId)
                                ->where('statut_contrat', 0)
                                ->first();

        // Retourner la réponse JSON
        return response()->json([
            'contratExistant' => $contratExistant ? true : false
        ]);
    }

    public function creercontrat(InscriptionCantineRequest $request){
        // Récupérer les informations de la requête

        // validation des donne 

        // dd("crercontrat");

            
                $data = $request->validated();
                // recuperer les donne entrer par l'utilisateur
                $classes = $request->input('classes');
                $eleveId = $request->input('matricules');
                $montant = $request->input('montant');
                $montantinteger = intval($montant);
                $idUserContrat = $request->input('id_usercontrat');
                // $dateContrat = $request->input('date');
                // dd($idUserContrat);
                $InfoUtilisateurConnecter =  User::where('id', $idUserContrat)->first();
                $id_usercontrat =  $InfoUtilisateurConnecter->id;
                $id_usercontratInt = intval($id_usercontrat);

                // $dateContrat = $request->input('date');
                // Récupérer la date avec l'heure depuis la requête
                $dateContrt = $request->input('date');
                
                // Convertir en objet Carbon
                $dateContratt = Carbon::parse($dateContrt);
                
                // Formater la date pour l'affichage
                $dateContrat = $dateContratt->format('Y-m-d H:i:s');
                // dd($dateContrt);


        // Si la date n'est pas spécifiée, utiliser la date du jour
        // if (empty($dateContrat)) {
        //     $dateContrat = date('Y-m-d H:i:s');
        // }
        // Trouver l'élève en fonction de la classe (CODECLAS)
         $elevy = Eleve::where('MATRICULE', $eleveId)->get();
        
                // Si la date n'est pas spécifiée, utiliser la date du jour
                // if (empty($dateContrat)) {
                //     $dateContrat = date('Y-m-d');
                // }
        
                // Trouver l'élève en fonction de la classe (CODECLAS)
                $elevy = Eleve::where('MATRICULE', $eleveId)->get();
                
                $nom = Eleve::where('MATRICULE', $eleveId)->value('NOM');
                $prenom = Eleve::where('MATRICULE', $eleveId)->value('PRENOM');
                $elevyo = $nom .' '. $prenom;



                if ($eleveId) {
                    // $eleveId = $eleve->MATRICULE;
        
                    // Chercher un contrat existant pour cet élève avec statut_contrat = 0
                    $contratExistant = Contrat::where('eleve_contrat', $eleveId)
                                               ->where('statut_contrat', 0)
                                               ->first();
        
                                               $paramse = Params2::first(); 
        
                                               $logoUrl = $paramse ? $paramse->logoimage: null; 
                                               $nometab = $paramse->NOMETAB; 
                                               $ifu = $paramse->ifu; 

                                            //    dd($ifu);
                    if ($contratExistant) {
                        // Mettre à jour le contrat existant
                        
                        $contratExistant->cout_contrat = $montant;
                        $contratExistant->id_usercontrat = $id_usercontrat;
                        $contratExistant->statut_contrat = 1;
                        $contratExistant->datecreation_contrat = $dateContrat;
                        $contratExistant->dateversion_contrat = $dateContrat;
                        $contratExistant->save();
                        // Session::put('amount', $montant);
                        // Session::put('classe', $classes);
                        // Session::put('logoUrl', $logoUrl);
                        // Session::put('dateContrat', $dateContrat);
                        // Session::put('elevyo', $elevyo);
                        // Session::put('nometab', $nometab);
                        // Session::put('ifu', $ifu);
        
                        // return view('pages.Etats.pdfinscription')
                        // ->with('amount', $montant)
                        // ->with('classe', $classes )
                        // ->with('logoUrl', $logoUrl )
                        // ->with('dateContrat', $dateContrat)
                        // ->with('elevyo', $elevyo)
                        // ->with('nometab', $nometab)
                        // ->with('ifu', $ifu);
                        
                        return back()->with('status', 'Contrat mis à jour avec succès');
                    } else {
                        // Créer un nouveau contrat

                        // verifier si montant < 0

                        if($montantinteger > 0) {
                            // code pour normalisation
                            $parametrefacture = Params2::first();
                            $ifuentreprise = $parametrefacture->ifu;
                            $tokenentreprise = $parametrefacture->token;
                            $taxe = $parametrefacture->taxe;
                            $type = $parametrefacture->typefacture;
                            
                    // -------------------------------
                        //  CREATION DE LA FACTURE
                    // -------------------------------
                    $items = []; // Initialiser un tableau vide pour les éléments

                    // foreach ($nomsMoisCoches as $idmois => $mois) {
                    //     $items[] = [
                    //         'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                    //         'price' => intval($montantmoiscontrat),
                    //         'quantity' => 1,
                    //         'taxGroup' => $taxe,
                    //     ];
                    // }

                    // dd($items);
                        // Préparez les données JSON pour l'API
                            $jsonData = json_encode([
                                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                // "aib" => "A",
                                "type" => $type,
                                "items" => [
                                    [
                                        'name' => 'Inscription cantine', // Pas besoin de $$ pour une variable
                                        'price' => intval($montant),
                                        'quantity' => 1,
                                        'taxGroup' => $taxe,
                                    ]
                                ],
                                "client" => [
                                    // "ifu" => " ",
                                    "name"=>  $elevyo,
                                    // "contact" => "string",
                                    // "address"=> "string"
                                ],
                                "operator" => [
                                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                ],
                                "payment" => [
                                    [
                                    "name" => "ESPECES",
                                    "amount" => intval($montant)
                                    ]
                                  ],
                            ]);
                        // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
    
    
                        //  dd($jsonData);
    
                        // Définissez l'URL de l'API de facturation
                        $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
    
                        // Définissez le jeton d'authentification
                        $token = $tokenentreprise;
                        // $token = $tokenentreprise;
    
                        // Effectuez la requête POST à l'API
                        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ));
                        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                    // Exécutez la requête cURL et récupérez la réponse
            $response = curl_exec($ch);
            // dd($response);
    
            // Vérifiez les erreurs de cURL
            if (curl_errno($ch)) {
                // echo 'Erreur cURL : ' . curl_error($ch);
                return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
            }


    
            // Fermez la session cURL
            curl_close($ch);
    
            // Affichez la réponse de l'API
            $decodedResponse = json_decode($response, true);
            //  dd($decodedResponse);
    
            // Vérifiez si l'UID est présent dans la réponse
            if (isset($decodedResponse['uid'])) {
                // L'UID de la demande
                $uid = $decodedResponse['uid'];
                // $taxb = 0.18;
    
                // Affichez l'UID
                // echo "L'UID de la demande est : $uid";
    
               
    

                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

                // Définissez l'URL de l'API de confirmation de facture
                $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
                // Configuration de la requête cURL pour la confirmation
                $chRecuperation = curl_init($recuperationUrl);
                curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
                // Exécutez la requête cURL pour la confirmation
                $responseRecuperation = curl_exec($chRecuperation);
                // dd($responseConfirmation);
                // Vérifiez les erreurs de cURL pour la confirmation

    
                // Fermez la session cURL pour la confirmation
                curl_close($chRecuperation);
    
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedDonneFacture = json_decode($responseRecuperation, true);
    
            $facturedetaille = json_decode($jsonData, true);
            $ifuEcoleFacture = $decodedDonneFacture['ifu'];
            // dd($ifuEcoleFacture);
            $itemFacture = $decodedDonneFacture['items'];
            $doneeDetailleItemFacture = $itemFacture['0'];
            $nameItemFacture = $doneeDetailleItemFacture['name'];
            $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
            $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
            $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
            // $idd = $responseRecuperation.ifu;
            $nameClient = $decodedDonneFacture['client']['name'];
            // dd($decodedDonneFacture);



                // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

                 // ACTION pour la confirmation
                 $actionConfirmation = 'confirm';

                // Définissez l'URL de l'API de confirmation de facture
                $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
            
                // Configuration de la requête cURL pour la confirmation
                $chConfirmation = curl_init($confirmationUrl);
                curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Length: 0'
                ]);
                curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
            
                // Exécutez la requête cURL pour la confirmation
                $responseConfirmation = curl_exec($chConfirmation);
            
            
                // Fermez la session cURL pour la confirmation
                curl_close($chConfirmation);
            
            // Convertissez la réponse JSON en tableau associatif PHP
            $decodedResponseConfirmation = json_decode($responseConfirmation, true);
            // dd($decodedResponseConfirmation);

    
                $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];
    
                $counters = $decodedResponseConfirmation['counters'];
    
                $nim = $decodedResponseConfirmation['nim'];
    
                $dateTime = $decodedResponseConfirmation['dateTime'];
    
    
                // Générer le code QR
                $qrCodeString = $decodedResponseConfirmation['qrCode'];
    
                $reffactures = $nim.'-'.$counters;
                // explode('/', $chaine)[0]

                $reffacture = explode('/', $reffactures)[0];
    
                // dd($reffacture);

            // gestion du code qr sous forme d'image
    
            $fileNameqrcode = $elevyo . time() . '.png';
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($qrCodeString)
                ->size(100)
                // ->margin(10)
                ->build();
    
                $qrcodecontent = $result->getString();

                $TOTALHT = $montant / 1.18;
                $totalHTArrondi = 0;
                $TOTALTVA = 0;

                $paramse = Params2::first(); 

                $logoUrl = $paramse ? $paramse->logoimage: null; 
            
                $NOMETAB = $paramse->NOMETAB;

                // dd($reffacture);

                            // ENREGISTREMENT DE LA FACTURE
                    $facturenormaliseinscription = new Facturenormaliseinscription();
                    $facturenormaliseinscription->id = $reffacture;
                    $facturenormaliseinscription->codemecef = $codemecef;
                    $facturenormaliseinscription->counters = $counters;
                    $facturenormaliseinscription->nim = $nim;
                    $facturenormaliseinscription->dateHeure = $dateTime;
                    $facturenormaliseinscription->ifuEcole = $ifuEcoleFacture;
                    $facturenormaliseinscription->MATRICULE = $eleveId;
                    // $facturenormalise->idcontrat = $idcontratEleve;
                    // $facturenormalise->moispayes = $moisConcatenes;
                    $facturenormaliseinscription->TOTALHT = $totalHTArrondi;
                    $facturenormaliseinscription->TOTALTVA = $TOTALTVA;
                    $facturenormaliseinscription->classe = $classes;
                    $facturenormaliseinscription->nom = $nameClient;
                    $facturenormaliseinscription->designation = $nameItemFacture;
                    $facturenormaliseinscription->montant_total = $prixTotalItemFacture;
                    $facturenormaliseinscription->datepaiementcontrat = $dateContrat;
                    $facturenormaliseinscription->qrcode = $qrcodecontent;
                    $facturenormaliseinscription->statut = 1;
                    
                    $facturenormaliseinscription->save();
                    // dd($facturenormaliseinscription);

                    $nouveauContrat = new Contrat();
                        $nouveauContrat->eleve_contrat = $eleveId;
                        $nouveauContrat->cout_contrat = $montant;
                        $nouveauContrat->id_usercontrat = $id_usercontratInt;
                        $nouveauContrat->statut_contrat = 1;
                        $nouveauContrat->datecreation_contrat = $dateContrat;
                        $nouveauContrat->dateversion_contrat = $dateContrat;
                        $nouveauContrat->save();

                        // Récupérer l'ID du contrat récemment créé
                        $idContratNouv = $nouveauContrat->id_contrat;

                        $infoParamContrat = Paramcontrat::first();
                        $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
                        $anneeSuivante = $debutAnneeEnCours + 1;
                        $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;

                        
                        // enregistrement dans paiementcontrat
                        $nouveauPaiementcontrat = new Paiementcontrat();
                        $nouveauPaiementcontrat->soldeavant_paiementcontrat = $prixTotalItemFacture;
                        $nouveauPaiementcontrat->montant_paiementcontrat = $prixTotalItemFacture;
                        $nouveauPaiementcontrat->soldeapres_paiementcontrat = 0;
                        $nouveauPaiementcontrat->id_contrat = $idContratNouv;
                        $nouveauPaiementcontrat->date_paiementcontrat = $dateContrat;
                        $nouveauPaiementcontrat->id_usercontrat = $id_usercontratInt;
                        $nouveauPaiementcontrat->mois_paiementcontrat = 13;
                        $nouveauPaiementcontrat->anne_paiementcontrat = $debutAnneeEnCours;
                        $nouveauPaiementcontrat->reference_paiementcontrat = $reffacture;
                        $nouveauPaiementcontrat->statut_paiementcontrat = 1;
                        $nouveauPaiementcontrat->montanttotal = $prixTotalItemFacture;
                        // $nouveauPaiementcontrat->dateversion_contrat = $dateContrat;
                        $nouveauPaiementcontrat->save();
                        // dd($dateContrt);

                        Session::put('factureconfirm', $decodedResponseConfirmation);
                        Session::put('fileNameqrcode', $fileNameqrcode);
                        Session::put('facturedetaille', $facturedetaille);
                        Session::put('reffacture', $reffacture);
                        Session::put('classeeleve', $classes);
                        Session::put('nomcompleteleve', $nameClient);
                        // Session::put('toutmoiscontrat', $toutmoiscontrat);
                        Session::put('nameItemFacture', $nameItemFacture);
                        Session::put('prixTotalItemFacture', $prixTotalItemFacture);
                        Session::put('quantityItemFacture', $quantityItemFacture);
                        Session::put('taxGroupItemFacture', $taxGroupItemFacture);
                        Session::put('ifuEcoleFacture', $ifuEcoleFacture);
                        Session::put('qrCodeString', $qrCodeString);
                        Session::put('itemFacture', $itemFacture);
                        Session::put('montanttotal', $montant);
                        Session::put('totalHTArrondi', $totalHTArrondi);
                        Session::put('TOTALTVA', $TOTALTVA);
                        Session::put('montantmoiscontrat', $montant);
                        Session::put('qrcodecontent', $qrcodecontent);
                        Session::put('NOMETAB', $NOMETAB);
                        Session::put('nim', $nim);
                        Session::put('datepaiementcontrat', $dateContrat);
                        Session::put('dateTime', $dateTime);

                        // return view('pages.Etats.pdfinscription')
                        //             ->with('amount', $montant)
                        //             ->with('classe', $classes )
                        //             ->with('logoUrl', $logoUrl )
                        //             ->with('dateContrat', $dateContrat)
                        //             ->with('nometab', $nometab)
                        //             ->with('ifu', $ifu)
                        //             ->with('elevyo', $elevyo);

                        return view('pages.Etats.pdfinscription', [
                            'factureconfirm' => $decodedResponseConfirmation,
                            'fileNameqrcode' => $fileNameqrcode,
                            'facturedetaille' => $facturedetaille,
                            'reffacture' => $reffacture,
                            'ifuEcoleFacture' => $ifuEcoleFacture,
                            'nameItemFacture' => $nameItemFacture,
                            'prixTotalItemFacture' => $prixTotalItemFacture,
                            'quantityItemFacture' => $quantityItemFacture,
                            'taxGroupItemFacture' => $taxGroupItemFacture,
                            'classeeleve' => $classes,
                            'nomcompleteleve' => $nameClient,
                            // 'toutmoiscontrat' => $toutmoiscontrat,
                            'qrCodeString' => $qrCodeString,
                            'logoUrl' => $logoUrl,
                            'itemFacture' => $itemFacture,
                            'montanttotal' => $montant,
                            'qrcodecontent' => $qrcodecontent,
                            'NOMETAB' => $NOMETAB,
                            'nim' => $nim,
                            'datepaiementcontrat' => $dateContrt,
                            'dateTime' => $dateTime,
                            'totalHTArrondi' => $totalHTArrondi,
                            'TOTALTVA' => $TOTALTVA,
                            // 'villeetab' => $villeetab,
                            // 'qrCodeImage' => $qrCodeImage,
                    
                                 ]);

            }
                        }else {
                            // montant = 0;

                            $nouvContrat = new Contrat();
                            $nouvContrat->eleve_contrat = $eleveId;
                            $nouvContrat->cout_contrat = $montant;
                            $nouvContrat->id_usercontrat = $id_usercontrat;
                            $nouvContrat->statut_contrat = 1;
                            $nouvContrat->datecreation_contrat = $dateContrt;
                            $nouvContrat->dateversion_contrat = $dateContrt;
                            $nouvContrat->save();

                            return back()->with('status', 'Contrat enregistrer avec succès');
                        }



                        // $nouveauContrat = new Contrat();
                        // $nouveauContrat->eleve_contrat = $eleveId;
                        // $nouveauContrat->cout_contrat = $montant;
                        // $nouveauContrat->id_usercontrat = $idUserContrat;
                        // $nouveauContrat->statut_contrat = 1;
                        // $nouveauContrat->datecreation_contrat = $dateContrat;
                        // $nouveauContrat->save();
                        // Session::put('amount', $montant);
                        // Session::put('classe', $classes);
                        // Session::put('logoUrl', $logoUrl);
                        // Session::put('dateContrat', $dateContrat);
                        // Session::put('elevyo', $elevyo);
                        // Session::put('nometab', $nometab);
                        // Session::put('ifu', $ifu);
                        // return view('pages.Etats.pdfinscription')
                        // ->with('amount', $montant)
                        // ->with('classe', $classes )
                        // ->with('logoUrl', $logoUrl )
                        // ->with('dateContrat', $dateContrat)
                        // ->with('nometab', $nometab)
                        // ->with('ifu', $ifu)
                        // ->with('elevyo', $elevyo);
                        // return redirect()->back()->with('success', 'Élève ajouté avec succès');
                    }
                } else {
                    return redirect()->back()->with('errors', 'Élève non trouvé');
                    // return back()->with('errors', 'Élève non trouvé');
                }

            // }
    }

public function savepaiementetinscriptioncontrat(Request $request) {
        // dd("crercontratetpaiement");
        // $data = $request->validated();
        // recuperer les donne entrer par l'utilisateur
        $classes = $request->input('classes');
        $eleveId = $request->input('matricules');
        $montant = $request->input('montant');
        $montantinteger = intval($montant);
        $idUserContrat = $request->input('id_usercontrat');
        // $dateContrat = $request->input('date');
        // dd($idUserContrat);
        $InfoUtilisateurConnecter =  User::where('id', $idUserContrat)->first();
        $id_usercontrat =  $InfoUtilisateurConnecter->id;
        $id_usercontratInt = intval($id_usercontrat);


        $moisCoches = $request->input('moiscontrat');
        $montantmoiscontrat = $request->input('montantcontrat');
        $montanttotal = $request->input('montanttotal');
        $datepaiementcontrat = $request->input('date');
        $montantParMoisReel = $request->input('montantcontratReel');
        $montantParMoisReelInt = intval($montantParMoisReel);
        $id_usercontrat = Session::get('id_usercontrat');
        // $dateContrat = $request->input('date');
        // Récupérer la date avec l'heure depuis la requête
        $dateContrt = $request->input('datePaiement');

        $anneeActuelle = date('Y');

        $infoParamContrat = Paramcontrat::first();
        $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
        $anneeSuivante = $debutAnneeEnCours + 1;
        $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;
        
        // Convertir en objet Carbon
        $dateContratt = Carbon::parse($dateContrt);
        
        // Formater la date pour l'affichage
        $dateContrat = $dateContratt->format('Y-m-d H:i:s');
        // dd($dateContrt);


        // Si la date n'est pas spécifiée, utiliser la date du jour
        // if (empty($dateContrat)) {
        //     $dateContrat = date('Y-m-d H:i:s');
        // }
        // Trouver l'élève en fonction de la classe (CODECLAS)
        $elevy = Eleve::where('MATRICULE', $eleveId)->get();

        // Si la date n'est pas spécifiée, utiliser la date du jour
        // if (empty($dateContrat)) {
        //     $dateContrat = date('Y-m-d');
        // }

        // Trouver l'élève en fonction de la classe (CODECLAS)
        $elevy = Eleve::where('MATRICULE', $eleveId)->get();
        
        $nom = Eleve::where('MATRICULE', $eleveId)->value('NOM');
        $prenom = Eleve::where('MATRICULE', $eleveId)->value('PRENOM');
        $elevyo = $nom .' '. $prenom;


        // dd($moisCoches);
                        // Array des noms des mois
                        $nomsMoisCoches = [];
                        if (is_array($moisCoches)) {
        
                            // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                            foreach ($moisCoches as $id_moiscontrat) {
                                // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                                $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                                
                                // Vérifiez si le mois existe
                                if ($mois) {
                                    // Ajouter le nom du mois à l'array
                                    $nomsMoisCoches[] = $mois->nom_moiscontrat;
                                }
                            }
                        }

                        $moisConcatenes = implode(',', $nomsMoisCoches);

                        $parametrefacture = Params2::first();
                        $ifuentreprise = $parametrefacture->ifu;
                        $tokenentreprise = $parametrefacture->token;
                        $taxe = $parametrefacture->taxe;
                        $type = $parametrefacture->typefacture;
                    
                        $parametreetab = Params2::first();

                        $moisavecvirg = implode(',', $nomsMoisCoches);
                        $toutmoiscontrat = $moisavecvirg;

                        // dd($moisavecvirg);
                        $items = []; // Initialiser un tableau vide pour les éléments

                        // AJOUT D’UNE LIGNE FIXE POUR INSCRIPTION
                        $items[] = [
                            'name'      => 'Frais cantine pour inscription',
                            'price'     => intval($montantinteger),
                            'quantity'  => 1,
                            'taxGroup'  => $taxe,
                        ];

                        foreach ($nomsMoisCoches as $idmois => $mois) {
                            $items[] = [
                                'name' => 'Frais cantine pour : ' . $mois, // Pas besoin de $$ pour une variable
                                'price' => intval($montantmoiscontrat ),
                                'quantity' => 1,
                                'taxGroup' => $taxe,
                            ];

                             // Définir $montantAPayer par défaut pour tous les mois
                        if (in_array($mois, ['Decembre', 'Septembre', 'Avril'])) {
                            // Montant spécifique pour certains mois
                            switch ($mois) {
                                case 'Decembre':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                                case 'Septembre':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                                case 'Avril':
                                    $montantAPayer = $montantParMoisReelInt;
                                    break;
                            }
                        } else {
                            // Montant par défaut pour les autres mois
                            $montantAPayer = $montantParMoisReelInt;
                        }

                         // Calculer le total des montants
                         $totalMontantinfoFacture = 0;

                         // Si $totalMontantinfoFacture est null, le remplacer par 0
                         $totalMontantinfoFacture = $totalMontantinfoFacture ?? 0;
 
                         // Calculer la somme des montants
                         $sommeDesMontant = $totalMontantinfoFacture + $montantmoiscontrat;
                         // dd($montantAPayer);
 
                         // Déterminer si le mois peut être sauvegardé
                         if ($sommeDesMontant < $montantAPayer) {
                             $saveMois = 1;                      
                         } else {
                             $saveMois = 0;
                         }

                    }
                    // dd($items);
            // Préparez les données JSON pour l'API
            $jsonData = json_encode([
                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                // "aib" => "A",
                "type" => $type,
                "items" => $items,

                "client" => [
                    // "ifu" => '',
                    "name"=>  $elevyo,
                    // "contact" => "string",
                    // "address"=> "string"
                ],
                "operator" => [
                    "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                ],
                "payment" => [
                    [
                    "name" => "ESPECES",
                      "amount" => intval($montanttotal + $montantinteger)
                    ]
                  ],
            ]);

            $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';

            $token = $tokenentreprise;

            // Effectuez la requête POST à l'API
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
            // Exécutez la requête cURL et récupérez la réponse
    $response = curl_exec($ch);

     // Vérifiez les erreurs de cURL
     if (curl_errno($ch)) {
        // echo 'Erreur cURL : ' . curl_error($ch);
        return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
    }
    
    // Fermez la session cURL
    curl_close($ch);
    
    // Affichez la réponse de l'API
    $decodedResponse = json_decode($response, true);
        // dd($decodedResponse);


         // Vérifiez si l'UID est présent dans la réponse
    if (isset($decodedResponse['uid'])) {
        // L'UID de la demande
        $uid = $decodedResponse['uid'];
        // $taxb = 0.18;
    
        // Affichez l'UID
        // echo "L'UID de la demande est : $uid";

        
                // -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

            // Définissez l'URL de l'API de confirmation de facture
            $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
            // Configuration de la requête cURL pour la confirmation
            $chRecuperation = curl_init($recuperationUrl);
            curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Length: 0'
            ]);
            curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

            // Exécutez la requête cURL pour la confirmation
            $responseRecuperation = curl_exec($chRecuperation);
            // dd($responseRecuperation);
            // Vérifiez les erreurs de cURL pour la confirmation


            // Fermez la session cURL pour la confirmation
            curl_close($chRecuperation);

        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedDonneFacture = json_decode($responseRecuperation, true);
        // dd($decodedDonneFacture);

        $facturedetaille = json_decode($jsonData, true);
        $ifuEcoleFacture = $decodedDonneFacture['ifu'];
        $itemFacture = $decodedDonneFacture['items'];
        $jsonItem = json_encode($itemFacture);
        $doneeDetailleItemFacture = $itemFacture['0'];
        $nameItemFacture = $doneeDetailleItemFacture['name'];
        $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
        $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
        $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
        // $idd = $responseRecuperation.ifu;
        $nameClient = $decodedDonneFacture['client']['name'];
        // dd($prixTotalItemFacture);

         // -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

        // ACTION pour la confirmation
        $actionConfirmation = 'confirm';
    
        // Définissez l'URL de l'API de confirmation de facture
        $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
    
        // Configuration de la requête cURL pour la confirmation
        $chConfirmation = curl_init($confirmationUrl);
        curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Length: 0'
        ]);
        curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
        // Exécutez la requête cURL pour la confirmation
        $responseConfirmation = curl_exec($chConfirmation);
    
        // Vérifiez les erreurs de cURL pour la confirmation
        if (curl_errno($chConfirmation)) {
            // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
            return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

        }
    
        // Fermez la session cURL pour la confirmation
        curl_close($chConfirmation);
    
    // Convertissez la réponse JSON en tableau associatif PHP
    $decodedResponseConfirmation = json_decode($responseConfirmation, true);
    // dd($decodedResponseConfirmation);
    
    
        $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

        $counters = $decodedResponseConfirmation['counters'];

        $nim = $decodedResponseConfirmation['nim'];

        $dateTime = $decodedResponseConfirmation['dateTime'];

        // Générer le code QR
        $qrCodeString = $decodedResponseConfirmation['qrCode'];

        $reffactures = $nim.'-'.$counters;

        $reffacture = explode('/', $reffactures)[0];

          // gestion du code qr sous forme d'image

          $fileNameqrcode = $elevyo  . time() . '.png';
          $result = Builder::create()
              ->writer(new PngWriter())
              ->data($qrCodeString)
              ->size(100)
              // ->margin(10)
              ->build();

              $qrcodecontent = $result->getString();

              $nouveauContrat = new Contrat();
              $nouveauContrat->eleve_contrat = $eleveId;
              $nouveauContrat->cout_contrat = $montant;
              $nouveauContrat->id_usercontrat = $id_usercontratInt;
              $nouveauContrat->statut_contrat = 1;
              $nouveauContrat->datecreation_contrat = $dateContrat;
              $nouveauContrat->dateversion_contrat = $dateContrat;
              $nouveauContrat->save();

              // Récupérer l'ID du contrat récemment créé
              $idContratNouv = $nouveauContrat->id_contrat;

              $infoParamContrat = Paramcontrat::first();
              $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
              $anneeSuivante = $debutAnneeEnCours + 1;
              $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;

              
              // enregistrement dans paiementcontrat
              $nouveauPaiementcontrat = new Paiementcontrat();
              $nouveauPaiementcontrat->soldeavant_paiementcontrat = $montantinteger;
              $nouveauPaiementcontrat->montant_paiementcontrat = $montantinteger;
              $nouveauPaiementcontrat->soldeapres_paiementcontrat = 0;
              $nouveauPaiementcontrat->id_contrat = $idContratNouv;
              $nouveauPaiementcontrat->date_paiementcontrat = $dateContrat;
              $nouveauPaiementcontrat->id_usercontrat = $id_usercontratInt;
              $nouveauPaiementcontrat->mois_paiementcontrat = 13;
              $nouveauPaiementcontrat->anne_paiementcontrat = $debutAnneeEnCours;
              $nouveauPaiementcontrat->reference_paiementcontrat = $reffacture;
              $nouveauPaiementcontrat->statut_paiementcontrat = 1;
              $nouveauPaiementcontrat->montanttotal = $montantinteger;
              // $nouveauPaiementcontrat->dateversion_contrat = $dateContrat;
              $nouveauPaiementcontrat->save();

              do {
                // Génère un nombre aléatoire entre 10000000 et 99999999
            $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
            } while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());

            // ENREGISTREMENT DANS LA TABLE INSCRIPTIONCONTRAT
                     // Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
                     foreach ($moisCoches as $id_moiscontrat) {
                        // $saveMois == 1;
                        if ($saveMois == 0) {
                            Inscriptioncontrat::create([
                                 // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                                 'id_contrat' => $idContratNouv,
                                 'id_moiscontrat' => $id_moiscontrat,
                                 'anne_inscription' => $debutAnneeEnCours,
                                
                             ]);
                        }else{
                            // 
                        }
                     }

                      // recuperer les nom des mois cochee

                    // Array des noms des mois
                    $nomsMoisCoches = [];

                    // Parcourir les ID des mois cochés et obtenir leur nom correspondant
                    foreach ($moisCoches as $id_moiscontrat) {
                        // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
                        $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                        
                        // Vérifiez si le mois existe
                        if ($mois) {
                            // Ajouter le nom du mois à l'array
                            $nomsMoisCoches[] = $mois->nom_moiscontrat;
                        }
                    }

                    // Convertir le tableau en une chaîne de caractères
                    $moisConcatenes = implode(',', $nomsMoisCoches);
                    // dd($moisConcatenes);
                    // Récupérer la somme des montants de paiement précédents
                    $soldeavant_paiementcontrat = DB::table('paiementglobalcontrat')
                    ->where('id_contrat', $idContratNouv)
                    ->sum('montant_paiementcontrat');


                    $InfoUtilisateurConnecter =  User::where('id', $id_usercontrat)->first();
                    $idUserCont =  $InfoUtilisateurConnecter->id;
                    $idUserContInt = intval($idUserCont);

                    // dd($soldeavant_paiementcontrat);
                    // Calculer le solde après le paiement en ajoutant le montant du paiement actuel à la somme des montants précédents
                    $soldeapres_paiementcontrat = $soldeavant_paiementcontrat + $montantmoiscontrat + $montantinteger;
                    // dd($soldeapres_paiementcontrat);

                      // ENREGISTREMENT DANS LA TABLE PAIEMENTGLOBALCONTRAT
                      $paiementglobalcontrat =  new Paiementglobalcontrat();
                        
                      $paiementglobalcontrat->soldeavant_paiementcontrat = $soldeavant_paiementcontrat;
                      $paiementglobalcontrat->montant_paiementcontrat = $montanttotal + $montantinteger;
                      $paiementglobalcontrat->soldeapres_paiementcontrat = $soldeapres_paiementcontrat;
                      $paiementglobalcontrat->id_contrat = $idContratNouv;
                      $paiementglobalcontrat->date_paiementcontrat = $datepaiementcontrat;
                          $paiementglobalcontrat->id_usercontrat = $idUserContInt;
                      $paiementglobalcontrat->anne_paiementcontrat = $debutAnneeEnCours;
                      $paiementglobalcontrat->reference_paiementcontrat = $valeurDynamiqueNumerique;
                      $paiementglobalcontrat->statut_paiementcontrat = 1;
                      //     $paiementglobalcontrat->datesuppr_paiementcontrat = null;
                      //    $paiementglobalcontrat->idsuppr_usercontrat = null;
                      //    $paiementglobalcontrat->motifsuppr_paiementcontrat = null;
                      $paiementglobalcontrat->mois_paiementcontrat = $moisConcatenes;
  
                      $paiementglobalcontrat->save();

                       // Récupérer l'id_paiementcontrat de la table paiementglobalcontrat qui correspond a l'id du contrat
                    $idPaiementContrat = Paiementglobalcontrat::where('id_contrat', $idContratNouv)
                    ->orderBy('id_paiementcontrat', 'desc')
                    ->value('id_paiementcontrat');
                    // dd($idPaiementContrat);                

                    // ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT

                    // dd($soldeavant_paiementcontrat);
                    // Créer un objet DateTime à partir de la chaîne de caractères
                    $datezz = new DateTime($datepaiementcontrat);

                    // Formater la date sans l'heure
                    $datezzSansHeure = $datezz->format('Y-m-d');  // Cela donnera "2025-02-18"

                    // Parcourir les mois cochés et insérer chaque id de mois dans la table Paiementcontrat
                    foreach ($moisCoches as $id_moiscontrat) {
                        Paiementcontrat::create([
                            // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
                            'soldeavant_paiementcontrat' => $soldeavant_paiementcontrat,
                            'montant_paiementcontrat' => $montantmoiscontrat ,
                            'soldeapres_paiementcontrat' => $soldeapres_paiementcontrat,
                            'id_contrat' => $idContratNouv,
                            'date_paiementcontrat' => $datepaiementcontrat,
                            // 'date_paiementcontrat' => $datezzSansHeure,
                            'id_usercontrat' => $idUserContInt,
                            'mois_paiementcontrat' => $id_moiscontrat,
                            'anne_paiementcontrat' => $debutAnneeEnCours,
                            'reference_paiementcontrat' => $valeurDynamiqueNumerique,
                            'statut_paiementcontrat' => 1,
                            // 'datesuppr_paiementcontrat' => $anneeActuelle,
                            // 'idsuppr_usercontrat' => $anneeActuelle,
                            // 'motifsuppr_paiementcontrat' => $anneeActuelle,
                            'id_paiementglobalcontrat' => $idPaiementContrat,
                            'montanttotal' => $montanttotal + $montantinteger
                        ]);
                    }




        // CALCUL DU TOTALHT ET TOTALTVA

        $TOTALHT = $montanttotal / 1.18;
        $totalHTArrondi = 0;
        $TOTALTVA = 0;

    // ********************************

    // dd($ifuEcoleFacture);
    $facturenormalise = new Facturenormalise();
    $facturenormalise->id = $reffacture;
    $facturenormalise->codemecef = $codemecef;
    $facturenormalise->counters = $counters;
    $facturenormalise->nim = $nim;
    $facturenormalise->dateHeure = $dateTime;
    $facturenormalise->ifuEcole = $ifuEcoleFacture;
    $facturenormalise->MATRICULE = intval($eleveId);
    $facturenormalise->idcontrat = $idContratNouv;
    $facturenormalise->moispayes = $moisConcatenes;
    $facturenormalise->classe = $classes;
    $facturenormalise->nom = $nameClient;
    $facturenormalise->itemfacture = $jsonItem;
    $facturenormalise->designation = 'Frais cantine pour: inscription et'.$moisConcatenes;
    $facturenormalise->montant_total = intval($montanttotal + $montantinteger);
    // $facturenormalise->TOTALHT = $totalHTArrondi;
    // $facturenormalise->TOTALTVA = $TOTALTVA;
    $facturenormalise->montant_par_mois = intval($montantmoiscontrat);
    $facturenormalise->datepaiementcontrat = $datepaiementcontrat;
    $facturenormalise->qrcode = $qrcodecontent;
    $facturenormalise->statut = 1;
    $facturenormalise->typefac = 1;
    $facturenormalise->montantInscription = intval($montantinteger);

    $facturenormalise->save();



    $paramse = Params2::first(); 

    $logoUrl = $paramse ? $paramse->logoimage: null; 

    $NOMETAB = $paramse->NOMETAB;

    Session::put('factureconfirm', $decodedResponseConfirmation);
    Session::put('fileNameqrcode', $fileNameqrcode);
    Session::put('facturedetaille', $facturedetaille);
    Session::put('reffacture', $reffacture);
    Session::put('classeeleve', $classes);
    Session::put('nomcompleteleve', $elevyo );
    Session::put('toutmoiscontrat', $toutmoiscontrat);
    Session::put('nameItemFacture', $nameItemFacture);
    Session::put('prixTotalItemFacture', $prixTotalItemFacture);
    Session::put('quantityItemFacture', $quantityItemFacture);
    Session::put('taxGroupItemFacture', $taxGroupItemFacture);
    Session::put('ifuEcoleFacture', $ifuEcoleFacture);
    Session::put('qrCodeString', $qrCodeString);
    Session::put('itemFacture', $itemFacture);
    Session::put('montanttotal', $montanttotal + $montantinteger);
    Session::put('totalHTArrondi', $totalHTArrondi);
    Session::put('TOTALTVA', $TOTALTVA);
    Session::put('montantmoiscontrat', $montantmoiscontrat);
    Session::put('qrcodecontent', $qrcodecontent);
    Session::put('NOMETAB', $NOMETAB);
    Session::put('nim', $nim);
    Session::put('datepaiementcontrat', $datepaiementcontrat);
    Session::put('dateTime', $dateTime);
    // Session::put('nometab', $nometab);
    // Session::put('villeetab', $villeetab);




        return view('pages.Etats.pdffacture', [
            'factureconfirm' => $decodedResponseConfirmation,
            'fileNameqrcode' => $fileNameqrcode,
            'facturedetaille' => $facturedetaille,
            'reffacture' => $reffacture,
            'ifuEcoleFacture' => $ifuEcoleFacture,
            'nameItemFacture' => $nameItemFacture,
            'prixTotalItemFacture' => $prixTotalItemFacture,
            'quantityItemFacture' => $quantityItemFacture,
            'taxGroupItemFacture' => $taxGroupItemFacture,
            'classeeleve' => $classes,
            'nomcompleteleve' => $elevyo ,
            'toutmoiscontrat' => $toutmoiscontrat,
            'qrCodeString' => $qrCodeString,
            'logoUrl' => $logoUrl,
            'itemFacture' => $itemFacture,
            'montanttotal' => $montanttotal + $montantinteger,
            // 'montantinscription' => $montantinteger,
            'qrcodecontent' => $qrcodecontent,
            'NOMETAB' => $NOMETAB,
            'nim' => $nim,
            'datepaiementcontrat' => $datepaiementcontrat,
            'dateTime' => $dateTime,
            'totalHTArrondi' => $totalHTArrondi,
            'TOTALTVA' => $TOTALTVA,
            // 'villeetab' => $villeetab,
            // 'qrCodeImage' => $qrCodeImage,

                ]);

                



        }
    }

    
   
                
    public function pdffacture(){
        return view('pages.pdffacture');
    }
                
    
    public function supprimercontrat(Request $request){

            // $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
        $MATRICULE = $request->input('matricule');
        // dd($MATRICULE);
        $contratss = Contrat::where('eleve_contrat', $MATRICULE)->get();
        // dd($contratss);
        if($contratss){

            foreach ($contratss as $contrat) {
                $contrat->statut_contrat = 0;
                $contrat->save();
            }

            // $idcontrat = $contratss->id_contrat;
            // dd($idcontrat);
            // passage du statut a 0
            // $contratss->statut_contrat = 0;
            // $contratss->update();
            // $contratss->update(['statut_contrat' => 0]);


            // suppression du contrat de la table paiementcontrat
            
            // $paiementcontrat = Paiementcontrat::where('id_contrat', $idcontrat)->get();
            // // dd($paiementcontrat);
            // // Vérifier si des enregistrements existent
            // if ($paiementcontrat->count() > 0) {
            //     // Parcourir chaque modèle et appeler delete() sur chaque modèle
            //     foreach ($paiementcontrat as $paiement) {
            //         $paiement->update(['statut_paiementcontrat' => 0]);
            //     }
            // }else{

            //     return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
            // }


            // suppression du contrat de la table inscriptioncontrat

            // $inscriptioncontratspe = Inscriptioncontrat::where('id_contrat', $idcontrat)->get();
            // // dd($paiementcontrat);
            // // Vérifier si des enregistrements existent
            // if ($inscriptioncontratspe->count() > 0) {
            //     // Parcourir chaque modèle et appeler delete() sur chaque modèle
            //     foreach ($inscriptioncontratspe as $paiementinscri) {
            //         $paiementinscri->delete();
            //     }
            // }else{

            //     return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
            // }


            // suppression du contrat de la table paiementglobalcontrat

            // $paiementglobal = Paiementglobalcontrat::where('id_contrat', $idcontrat)->get();
            // // dd($paiementcontrat);
            // // Vérifier si des enregistrements existent
            // if ($paiementglobal->count() > 0) {
            //     // Parcourir chaque modèle et appeler delete() sur chaque modèle
            //     foreach ($paiementglobal as $paiementglob) {
            //         $paiementglob->update(['statut_paiementcontrat' => 0]);
            //     }
            // }else{

            //     return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");
            // }

            // $paiementcontrat->delete();
            return back()->with("status", "Le contrat a ete supprimer avec succes");

        }else{
            return back()->with("status", "Le contrat n'existe pas,  veuiller d'abord le creer pour l'eleve");

        }
                
    }
                

   
     public function traitementetatpaiement(Request $request)
    {
        set_time_limit(300); // Autorise jusqu'à 5 minutes si besoin

        $debut = $request->input('debut');
        $fin = $request->input('fin');

        // Préchargement des données nécessaires pour éviter les requêtes dans la boucle
        $contrats = Contrat::all()->keyBy('id_contrat');
        $eleves = Eleve::all()->keyBy('MATRICULE');
        $moisList = Moiscontrat::all()->keyBy('id_moiscontrat');
        $user = Auth::user();
    //   dd($user);
        $paiementsAvecEleves = collect([]);

        // Traitement par chunk pour éviter la surcharge mémoire
        Paiementcontrat::whereBetween('date_paiementcontrat', [$debut, $fin])
            ->where('statut_paiementcontrat', '=', 1)
            ->where('montant_paiementcontrat', '>', 1)
            ->chunk(100, function ($paiements) use (
                &$paiementsAvecEleves, 
                $contrats, 
                $eleves, 
                $moisList, 
                $user,
            ) {
                foreach ($paiements as $paiement) {
                    if ($paiement->mois_paiementcontrat == 13 && $paiement->montant_paiementcontrat == 0) {
                        continue;
                    }

                    $contrat = $contrats->get($paiement->id_contrat);
                    if (!$contrat) continue;

                    $eleve = $eleves->get($contrat->eleve_contrat);
                    if (!$eleve) continue;

                    $moisContrat = $moisList->get($paiement->mois_paiementcontrat);
                    $datePaiement = \Carbon\Carbon::parse($paiement->date_paiementcontrat)->format('Y-m-d');

                    // Vérifier si un paiement pour cet élève à cette date existe déjà
                    $existingPaiementIndex = $paiementsAvecEleves->search(function ($item) use ($eleve, $datePaiement) {
                        return $item['nomcomplet_eleve'] === $eleve->NOM . ' ' . $eleve->PRENOM &&
                            $item['date_paiement'] === $datePaiement;
                    });

                    if ($existingPaiementIndex !== false) {
                        // Paiement déjà existant : mise à jour
                        $updatedPaiement = $paiementsAvecEleves->get($existingPaiementIndex);
                        $updatedPaiement['mois'] .= ', ' . ($moisContrat ? $moisContrat->nom_moiscontrat : 'Mois inconnu');
                        $updatedPaiement['montant'] += $paiement->montant_paiementcontrat;
                        $paiementsAvecEleves->put($existingPaiementIndex, $updatedPaiement);
                    } else {
                        // Nouveau paiement : ajout
                 
                        $paiementsAvecEleves->push([
                            
                            'user' => $user->login,
                            'id_contrat' => $paiement->id_contrat,
                            'nomcomplet_eleve' => $eleve->NOM . ' ' . $eleve->PRENOM,
                            'classe_eleve' => $eleve->CODECLAS,
                            'id_paiementcontrat' => $paiement->id_paiementcontrat,
                            'date_paiement' => $paiement->date_paiementcontrat,
                            'montant' => $paiement->montant_paiementcontrat,
                            'mois' => $moisContrat ? $moisContrat->nom_moiscontrat : 'Mois inconnu',
                            'reference' => $paiement->reference_paiementcontrat,
                        ]);
                    }
                }
            });

        // Mise en forme des dates
        $dateObjdebut = DateTime::createFromFormat('Y-m-d', $debut);
        $dateObjfin = DateTime::createFromFormat('Y-m-d', $fin);
        $dateFormateedebut = $dateObjdebut->format('d/m/Y');
        $dateFormateefin = $dateObjfin->format('d/m/Y');

        // Stocker les résultats en session
        Session::put('paiementsAvecEleves', $paiementsAvecEleves);
        Session::put('dateFormateedebut', $dateFormateedebut);
        Session::put('dateFormateefin', $dateFormateefin);

        // Redirection avec ou sans résultats
        if ($paiementsAvecEleves->isEmpty()) {
            return redirect('etat')
                ->with('status', 'Aucun paiement trouvé pour la période spécifiée.')
                ->with('paiementsAvecEleves', $paiementsAvecEleves);
        } else {
            return view('pages.etatpaiement1')
                ->with('paiementsAvecEleves', $paiementsAvecEleves)
                ->with('dateFormateedebut', $dateFormateedebut)
                ->with('dateFormateefin', $dateFormateefin);
        }
    }



    public function etatpaiement1 (){
        $paiementsAvecEleves = Session::get('paiementsAvecEleves', collect()); // Déclaration avec une collection vide par défaut
        $dateFormateedebut = Session::get('dateFormateedebut'); 
        $dateFormateefin = Session::get('dateFormateefin'); 
        // dd($debut);

        return view('pages.etatpaiement1')->with('paiementsAvecEleves', $paiementsAvecEleves)->with('dateFormateedebut', $dateFormateedebut)->with('dateFormateefin', $dateFormateefin);
    }
            
            public function supprimerpaiement($id_paiementcontrat){

                $paiementsAvecEleves = Session::get('paiementsAvecEleves', collect()); // Déclaration avec une collection vide par défaut

                // $paiementsAvecEleves = Session::get('paiementsAvecEleves');
                                // suppression du contrat de la table paiementglobalcontrat

                                $paiementglobal = Paiementglobalcontrat::where('id_paiementcontrat', $id_paiementcontrat)->get();
                                // dd($paiementcontrat);
                                // Vérifier si des enregistrements existent
                                if ($paiementglobal->count() > 0) {
                                    // Parcourir chaque modèle et appeler delete() sur chaque modèle
                                    foreach ($paiementglobal as $paiementglob) {
                                        $paiementglob->update(['statut_paiementcontrat' => 0]);
                                    }
                                    // return view('pages.etatpaiement1')->with("statuspaiement", "Le paiement a ete supprimer avec succes")->with('paiementsAvecEleves', $paiementsAvecEleves);


                                    $paiementcontrat = Paiementcontrat::where('id_paiementglobalcontrat', $id_paiementcontrat)->get();
                                    // dd($paiementsAvecEleves);
                                    // Vérifier si des enregistrements existent
                                    if ($paiementcontrat->count() > 0) {
                                        // Parcourir chaque modèle et appeler delete() sur chaque modèle
                                        foreach ($paiementcontrat as $paiement) {
                                            $paiement->update(['statut_paiementcontrat' => 0]);
                                        }
                                        $message = "Le paiement a été supprimé avec succès.";
                                        return redirect('etatpaiement1')->with("statuspaiement", $message);                                    
                                    
                                    }else{
                                        return redirect('etatpaiement1')->with("statuspaiement", "Pas de paiement pour cet eleve,  veuillez dabord effectue un paiement");

                                    }
                    

                                    
                                }else{
                
                                    return redirect('etatpaiement1')->with("statuspaiement", "Pas de paiement pour cet eleve,  veuillez dabord effectue un paiement");
                                }

    }


    public function imprimerfiche($idpaiementcontrat){

        // dd($idpaiementcontrat);
        $lignepaiement = Paiementglobalcontrat::where('id_paiementcontrat', $idpaiementcontrat)->first();
        // dd($lignepaiement);

        $solde = $lignepaiement->montant_paiementcontrat;
        $id_contrat = $lignepaiement->id_contrat;
        $date_paiementcontrat = $lignepaiement->date_paiementcontrat;
        $anne_paiementcontrat = $lignepaiement->anne_paiementcontrat;
        $mois = $lignepaiement->mois_paiementcontrat;

        // recuperation des information de l'eleve

        $infocontrat = Contrat::where('id_contrat', $id_contrat)->first();
        $id_eleve = $infocontrat->eleve_contrat;

        $infoeleves = Eleve::where('MATRICULE', $id_eleve)->first();
        $nomcompeleve = $infoeleves->NOM .' '. $infoeleves->PRENOM;
        $classeeleve = $infoeleves->CODECLAS;
        // dd($classeeleve);

        return view('pages.etat.imprimerfiche')
        ->with('solde', $solde)
        ->with('date_paiementcontrat', $date_paiementcontrat)
        ->with('mois', $mois)
        ->with('nomcompeleve', $nomcompeleve)
        ->with('classeeleve', $classeeleve);
    }


 
                
                // public function traiter(Request $request)
                // {
                    //     // Récupérer l'ID de l'élève à partir de la requête
                    //     $eleveNom = $request->input('eleveNom');
                    //     dd($eleveNom);
                    
                    //     // Utiliser l'ID de l'élève pour effectuer des opérations dans votre contrôleur
                    //     // Par exemple, charger les détails de l'élève à partir de la base de données
                    
                    //     // Retourner une réponse (si nécessaire)
                    //     return response()->json(['message' => 'Informations de l\'élève traitées avec succès.']);
                    // }
                    // public function getElevesByClasse($CODECLAS) {
                        //     $elevess = Eleve::where('CODECLAS', $CODECLAS)->get();
                        
                        //     // dd($elevess);// Récupérer les élèves de la classe sélectionnée en fonction de $codeClasse
                        //     return view('pages.partial')->with('elevess', $elevess);
                        //   }
                        
                        // public function creercontrat(Request $request){
                            //     $matricules = $request->input('matricules');
                            //     $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
                            //     if($existingContrat) {
                                //         return back()->with('status', 'Un contrat existe déjà pour cet élève.');
                                //     }
                                //     $contra = new Contrat();
                                //     $contra->eleve_contrat = $matricules;
                                //     $contra->cout_contrat = $request->input('montant');
                                
                                //     $contra->datecreation_contrat = $request->input('date');
                                //     $contra->save();
                                
                                //     return back()->with('status','Contrat enregistré avec succès');
                                // }
                     
  

        // fin facture normalisee pour tous les paiements de l'annee 2023_2024                     
                            
}