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

    public function optionsbulletindenotes(Request $request) {
        $option = $request->all();

        Session::put('option', $option);
        // dd($option);
    }

    public function printbulletindenotes(Request $request)
    {
        $option = Session::get('option');
        $moyennesParClasseEtMatiere = [];
    
        $paramselection = $request->input('paramselection');
        $bonificationType = $request->input('bonificationType');
        $bonifications = $request->input('bonification');
        $msgEnBasBulletin = $request->input('msgEnBasBulletin');
        $periode = $request->input('periode');
        $conduite = $request->input('conduite'); // Code de la matière pour la conduite
        $eps = $request->input('eps');
        $nbabsence = $request->input('nbabsence');
        $apartirde = $request->input('apartirde');
        $classeSelectionne = $request->input('selected_classes', []);
    
        $params2 = Params2::first();
        $entete = Params2::first()->EnteteBull; // Récupérer la valeur de l'en-tête

        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours . '-' . $annesuivante;
    
        // Filtrer le tableau en enlevant l'élément 'all'
        $classeSelectionne = array_filter($classeSelectionne, function ($value) {
            return $value !== 'all';
        });
    
        // Traite chaque intervalle de bonification
        foreach ($bonifications as $bonification) {
            $start = $bonification['start'];
            $end = $bonification['end'];
            $note = $bonification['note'];
            // Code pour traiter les bonifications si nécessaire
        }
    
        // Récupérer les élèves dans les classes sélectionnées
        $eleves = Eleve::whereIn('CODECLAS', $classeSelectionne)
            ->with(['notes' => function ($query) {
                $query->where('SEMESTRE', 1); // Récupérer uniquement les notes du semestre 1
            }])->get();
    
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
                'redoublant' => $eleve->STATUT,
                'aptitute_sport' => $eleve->APTE,
                'matricule' => $eleve->MATRICULE,
                'anneScolaire' => $annescolaire,
                'periode' => "1er Trimestre",
                'classe' => $eleve->CODECLAS,
                'effectif' => $effectifsParClasse[$eleve->CODECLAS] ?? 0,
                'matieres' => []
            ];
    
            // Grouper les notes par matière
            $notesParMatiere = $eleve->notes->groupBy('CODEMAT');
    
            foreach ($notesParMatiere as $codeMatiere => $notes) {
                // Vérifier si la matière est la conduite
                if ($codeMatiere == $conduite || $codeMatiere == $eps) {
                    // Chercher la première note non nulle et différente de 21 et 0 dans les colonnes INT1, INT2, INT3, DEV1, DEV2, DEV3
                    $noteSpeciale = null;
                    foreach ($notes as $note) {
                        $noteSpeciale = collect([$note->INT1, $note->INT2, $note->INT3, $note->DEV1, $note->DEV2, $note->DEV3])
                            ->first(function ($value) {
                                return $value !== null && $value != 21 && $value != 0;
                            });
                
                        if ($noteSpeciale !== null) {
                            break; // On a trouvé une note valide, donc on peut sortir de la boucle
                        }
                    }
                
                    // Assigner les informations de la matière avec la note récupérée
                    $mentionMatSpecial = $this->determineMention($noteSpeciale, $params2);

                    $resultatEleve['matieres'][] = [
                        'code_matiere' => $codeMatiere,
                        'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? ($codeMatiere == $conduite ? 'Conduite' : 'EPS'),
                        'moyenne_sur_20' => $noteSpeciale,
                        'coefficient' => 1,
                        'mentionProf' => $mentionMatSpecial, // Pas de mention pour la conduite ou EPS
                    ];
                    continue; // Passer à la matière suivante
                }
    
                $totalDevoir = 0;
                $nbDevoir = 0;
                $totalCoeff = 0;
                $nomMatiere = $notes->first()->matiere->LIBELMAT ?? 'Nom de la matière non trouvé';
    
                foreach ($notes as $note) {
                    // Calculer les moyennes de devoirs et d'interrogations
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
    
                // Calculer les moyennes et les mentions
                $moyenneInterro = ($note->MI ?? 0) < 21 ? $note->MI : 0;
                $moyenneDevoir = $nbDevoir > 0 ? $totalDevoir / $nbDevoir : 0;
                $moyenneSur20 = $nbDevoir > 0 ? ($moyenneInterro + $totalDevoir) / ($nbDevoir + 1) : $moyenneInterro;
                $moyenneCoeff = $totalCoeff > 0 ? $moyenneSur20 * $totalCoeff : 0;
    
                // Stocker les moyennes pour chaque élève et matière
                $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                    'eleve_id' => $eleve->MATRICULE,
                    'moyenne' => $moyenneSur20
                ];
    
                // Déterminer la mention du professeur
                $mentionProf = $this->determineMention($moyenneSur20, $params2);
    
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
                usort($moyennes, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);
    
                $maxMoyenne = max(array_column($moyennes, 'moyenne'));
                $minMoyenne = min(array_column($moyennes, 'moyenne'));
    
                $rang = 1;
                foreach ($moyennes as $index => $item) {
                    $rangAttribue = $index > 0 && $item['moyenne'] == $moyennes[$index - 1]['moyenne'] ? $rang : $index + 1;
                    $rang = $rangAttribue;
    
                    foreach ($resultats as &$resultatEleve) {
                        if ($resultatEleve['matricule'] == $item['eleve_id']) {
                            foreach ($resultatEleve['matieres'] as &$matiereResultat) {
                                if ($matiereResultat['code_matiere'] == $matiere) {
                                    $matiereResultat['rang'] = $rangAttribue;
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
    
        // dd($resultats);
        return view('pages.notes.printbulletindenotes', compact('request', 'resultats', 'eleves', 'option', '$entete'));
    }
    
    /**
     * Détermine la mention du professeur en fonction de la moyenne.
     */
    private function determineMention($moyenne, $params2)
    {
        if ($moyenne < $params2->Borne1) {
            return $params2->Mention1p;
        } elseif ($moyenne <= $params2->Borne2) {
            return $params2->Mention2p;
        } elseif ($moyenne <= $params2->Borne3) {
            return $params2->Mention3p;
        } elseif ($moyenne <= $params2->Borne4) {
            return $params2->Mention4p;
        } else {
            return $params2->Mention5p;
        }
    }
    
}           
