<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Moiscontrat;
use App\Models\Paramcontrat;
use App\Models\Contrat;
use App\Models\Paiementcontrat;
use App\Models\Paiementglobalcontrat;
use App\Models\Usercontrat;
use App\Models\Paramsfacture;
use App\Models\User;
use App\Models\Params2;
use App\Models\Classes;
use App\Models\Departement;
use App\Models\Reduction;
use App\Models\Promo;
use App\Models\Serie;
use App\Models\Typeclasse;
use App\Models\Typeenseigne;
use App\Models\Elevea;
use App\Models\Groupeclasse;
use App\Models\Eleveplus;
use App\Models\Echeance;
use App\Models\Echeancc;
use App\Models\Scolarite;
use App\Models\Classesgroupeclass;
use App\Models\Journal;
use App\Models\Chapitre;
use App\Models\Deleve;
use App\Models\Eleve_pont;
use App\Models\Matiere;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Duplicatafacture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class BulletinController extends Controller
{
    public function bulletindenotes()
    {
        $classes = Classes::withCount(['eleves' => function ($query) {
            $query->where('CODECLAS', '!=', '');
        }])->get();
        $typeenseigne = Typeenseigne::all();
        $promotions = Promo::all();
        $matieres = Matiere::all();
        $eleves = Eleve::all();
        return view('pages.notes.bulletindenotes', compact('classes', 'typeenseigne', 'promotions', 'eleves', 'matieres'));
    }

    public function getClassesByType($type)
    {
        // dd($type);
        // Récupère les classes associées au type d'enseignement
        $classes = Classes::where('TYPEENSEIG', $type)->withCount('eleves')->get();
        // $classes = Classes::where('TYPEENSEIG', $type)->get();

        return response()->json($classes);
    }


    public function storebulletindenotes(Request $request)
    {

        dd($request->all());
        return redirect()->route('printbulletindenotes', $request->all());
    }

    public function printbulletindenotes(Request $request) {
        // Initialiser la variable $moyennesParClasseEtMatiere
        $moyennesParClasseEtMatiere = [];

        $paramselection = $request->input('paramselection');
        $bonificationType = $request->input('bonificationType');
        $bonifications = $request->input('bonification');
        $periode = $request->input('periode');
        $apartirde = $request->input('apartirde');
        $classeSelectionne = $request->input('selected_classes', []);

        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours.'-'.$annesuivante;

        // filtrer le tableau en enlevant l'élément 'all'
        $classeSelectionne = array_filter($classeSelectionne, function($value) {
            return $value !== 'all';
        });

        // Parcours chaque ligne de bonification
        foreach ($bonifications as $bonification) {
            $start = $bonification['start'];
            $end = $bonification['end'];
            $note = $bonification['note'];
            
            // Traite chaque intervalle de bonification ici
        }

        // Récupérer les élèves dans les classes sélectionnées
        $eleves = Eleve::whereIn('CODECLAS', $classeSelectionne)
            ->with(['notes' => function ($query) {
                $query->where('SEMESTRE', 1); // Récupérer uniquement les notes du semestre 1
            }])->get();

            // dd($eleves);

        // Calculer l'effectif de chaque classe sélectionnée
        $effectifsParClasse = Eleve::whereIn('CODECLAS', $classeSelectionne)
            ->select('CODECLAS')
            ->groupBy('CODECLAS')
            ->selectRaw('COUNT(*) as effectif')
            ->pluck('effectif', 'CODECLAS');

        $resultats = [];

        // Parcourir chaque élève pour calculer les moyennes
        foreach ($eleves as $eleve) {
            $resultatEleve = [
                'nom' => $eleve->NOM,
                'prenom' => $eleve->PRENOM,
                'redoublant' => $eleve->STATUTG,
                'matricule' => $eleve->MATRICULE,
                'anneScolaire' => $annescolaire,
                'periode' => "1er Trimestre",
                'classe' => $eleve->CODECLAS,
                'effectif' => $effectifsParClasse[$eleve->CODECLAS] ?? 0, // Effectif de la classe spécifique
                'matieres' => []
            ];

            // Grouper les notes par matière
            $notesParMatiere = $eleve->notes->groupBy('CODEMAT');

            // dd($notesParMatiere);

            foreach ($notesParMatiere as $codeMatiere => $notes) {
                $totalInterro = 0;
                $nbInterro = 0;
                $totalDevoir = 0;
                $nbDevoir = 0;
                $totalNotes = 0;
                $totalCoeff = 0;

                $nomMatiere = $notes->first()->matiere->LIBELMAT ?? 'Nom de la matière non trouvé';

                foreach ($notes as $note) {
                    // Filtrer et additionner les notes de devoir (DEV1, DEV3, DEV4)
                    foreach (['DEV1', 'DEV2', 'DEV3'] as $devCol) {
                        if (isset($note->$devCol) && $note->$devCol <= 20) {
                            $totalDevoir += $note->$devCol;
                            $nbDevoir++;
                        }
                    }

                    $totalCoeff += $note->COEF;
                    $dev1 = $note->DEV1;
                    $dev2 = $note->DEV2;
                    $dev3 = $note->DEV3;
                    
                    if ($note->TEST < 21) {
                        $test = $note->TEST;  
                    } else {
                        $test = null;
                    }
                }

                // Calcul de la moyenne d'interrogation par matière
                if ($note->MI < 21) {
                    $moyenneInterro = $note->MI;  
                } else {
                    $moyenneInterro = 0;
                }

                // Calcul de la moyenne des devoirs
                $moyenneDevoir = $nbDevoir > 0 ? $totalDevoir / $nbDevoir : 0;

                // Calcul de la moyenne sur 20 (50% interro + 50% devoirs)
                $moyenneSur20 = $nbDevoir > 0 ? ($moyenneInterro + $totalDevoir) / ($nbDevoir + 1) : $moyenneInterro;

                // Calcul de la moyenne coefficientée
                $moyenneCoeff = $totalCoeff > 0 ? $moyenneSur20 * $totalCoeff : 0;

                // Stocker les moyennes pour chaque élève et matière
                $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                    'eleve_id' => $eleve->MATRICULE,
                    'moyenne' => $moyenneSur20
                ];

                // Mention du proffeseur
                $params2 = Params2::first();
                $Borne1params2 = $params2->Borne1;
                $Borne2params2 = $params2->Borne2;
                $Borne3params2 = $params2->Borne3;
                $Borne4params2 = $params2->Borne4;
                $Borne5params2 = $params2->Borne5;
                $Borne6params2 = $params2->Borne6;
                $Borne7params2 = $params2->Borne7;
                $Borne8params2 = $params2->Borne8;

                if ($moyenneSur20 < $Borne1params2){
                    $mentionProf = $params2->Mention1p;
                } elseif (($moyenneSur20 > $Borne1params2) && ($moyenneSur20 <= $Borne2params2) ) {
                    $mentionProf = $params2->Mention2p;
                } elseif (($moyenneSur20 > $Borne2params2) && ($moyenneSur20 <= $Borne3params2) ) {
                    $mentionProf = $params2->Mention3p;
                } elseif (($moyenneSur20 > $Borne3params2) && ($moyenneSur20 <= $Borne4params2) ) {
                    $mentionProf = $params2->Mention4p;
                } elseif (($moyenneSur20 > $Borne4params2) && ($moyenneSur20 <= $Borne5params2) ) {
                    $mentionProf = $params2->Mention5p;
                } elseif (($moyenneSur20 > $Borne5params2) && ($moyenneSur20 <= $Borne6params2) ) {
                    $mentionProf = $params2->Mention6p;
                } elseif (($moyenneSur20 > $Borne6params2) && ($moyenneSur20 <= $Borne7params2) ) {
                    $mentionProf = $params2->Mention7p;
                } else {
                    $mentionProf = $params2->Mention8p;
                }

                // Stocker les informations pour la matière
                $resultatEleve['matieres'][] = [
                    'code_matiere' => $codeMatiere,
                    'nom_matiere' => $nomMatiere,
                    'moyenne_interro' => $moyenneInterro,
                    'devoir1' => $dev1,
                    'devoir2' => $dev2,
                    'devoir3' => $dev3,
                    'test' => $test,
                    'moyenne_sur_20' => $moyenneSur20,
                    'moyenne_coeff' => $moyenneCoeff,
                    'coefficient' => $totalCoeff,
                    'mentionProf' => $mentionProf,
                ];
            }

            $resultats[] = $resultatEleve;
        }

        // Calculer le rang pour chaque matière et chaque classe
        foreach ($moyennesParClasseEtMatiere as $classe => $matieres) {
            foreach ($matieres as $matiere => $moyennes) {
                // Trier les élèves par moyenne décroissante
                usort($moyennes, function($a, $b) {
                    return $b['moyenne'] <=> $a['moyenne'];
                });

                $maxMoyenne = max(array_column($moyennes, 'moyenne'));
                $minMoyenne = min(array_column($moyennes, 'moyenne'));

                // Assigner les rangs
                $rang = 1;
                foreach ($moyennes as $index => $item) {
                    // Vérifier si deux moyennes sont égales, si oui, attribuer le même rang
                    if ($index > 0 && $item['moyenne'] == $moyennes[$index - 1]['moyenne']) {
                        $rangAttribue = $rang; // Même rang pour les moyennes égales
                    } else {
                        $rangAttribue = $index + 1; // Nouveau rang
                        $rang = $rangAttribue;
                    }

                    // Assigner le rang à l'élève dans les résultats finaux
                    foreach ($resultats as &$resultatEleve) {
                        if ($resultatEleve['matricule'] == $item['eleve_id']) {
                            foreach ($resultatEleve['matieres'] as &$matiereResultat) {
                                if ($matiereResultat['code_matiere'] == $matiere) {
                                    $matiereResultat['rang'] = $rangAttribue; // Ajouter le rang à la matière
                                    $matiereResultat['plusForteMoyenne'] = $maxMoyenne;
                                    $matiereResultat['plusFaibleMoyenne'] = $minMoyenne;
                                    break;                               
                                }
                            }
                        }
                    }
                }
            }
        }

        // Retourner les résultats, ou effectuer d'autres actions
        dd ($resultats);
        
        return view('pages.notes.printbulletindenotes', compact('request'));
    }
}           
