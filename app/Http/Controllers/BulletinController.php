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
use Illuminate\Support\Facades\Schema;

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
  
  
  private function extractTextFromRtf($rtfString) {
    // Supprime les balises de groupe, les symboles de contrôle, et les mots de contrôle non suivis par un espace (typiquement des commandes de formatage)
    $text = preg_replace('/\{\*?\\[^{}]+}|[{}]|\\\[^\\s]+(\s+)?/s', '', $rtfString);
    // Supprime les fragments restants qui pourraient être des noms de police, des versions de logiciel, etc.
    $text = preg_replace('/[A-Za-z0-9]+;/', '', $text);
    // Supprime les métadonnées spécifiques comme les versions de logiciel
    $text = preg_replace('/\w+[\d.]+\w+/', '', $text);
    // Supprime les espaces supplémentaires et les retours à la ligne
    $text = trim(preg_replace('/\s+/', ' ', $text));
    return $text;
  }
  
  
  public function printbulletindenotes(Request $request)
  {
    $option = Session::get('option');
    // dd($option);
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
    $typean = $params2->TYPEAN;
    $rtfContent = Params2::first()->EnteteBull;
    $entete = $this->extractTextFromRtf($rtfContent);
    
    $infoparamcontrat = Paramcontrat::first();
    $anneencours = $infoparamcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours . '-' . $annesuivante;
    
    // Filtrer le tableau en enlevant l'élément 'all'
    $classeSelectionne = array_filter($classeSelectionne, function ($value) {
      return $value !== 'all';
    });
    $clases = DB::table('eleve')->select('CODECLAS')->distinct()->get();
    
    foreach ($clases as $classe) {
      // Récupérer les élèves d'une classe triés par MAN (moyenne annuelle)
      $eleves = DB::table('eleve')
      ->where('CODECLAS', $classe->CODECLAS)
      ->whereNotNull('MAN')
      ->orderBy('MAN', 'desc')
      ->get();
      
      $rank = 1; // Rang initial
      $previousMan = null; // Pour vérifier les égalités
      
      foreach ($eleves as $eleve) {
        if ($previousMan !== null && $eleve->MAN !== $previousMan) {
          $rank++; // Incrément du rang seulement si la moyenne est différente
        }
        
        // Mise à jour du rang annuel
        DB::table('eleve')
        ->where('MATRICULE', $eleve->MATRICULE)
        ->update(['RANGA' => $rank]);
        
        $previousMan = $eleve->MAN; // Stocker la moyenne pour comparaison
      }
    }
    $eles = DB::table('eleve')->get();
    
    foreach ($eles as $eleve) {
      $notes = [];
      $notes = [];
      for ($i = 1; $i <= 12; $i++) {
          $ms = $eleve->{'MS' . $i}; // Accéder dynamiquement aux colonnes MS1, MS2, ...
          if ($ms !== null && $ms != -1) {
              $notes[] = $ms;
          }
      }
      if (!empty($notes)) {
        $man = array_sum($notes) / count($notes);
        DB::table('eleve')
        ->where('MATRICULE', $eleve->MATRICULE)
        ->update(['MAN' => $man]);
      }
    }
    // Traite chaque intervalle de bonification
    foreach ($bonifications as $bonification) {
      $start = $bonification['start'];
      $end = $bonification['end'];
      $note = $bonification['note'];
      // Code pour traiter les bonifications si nécessaire
    }
    ///Calculere moyenne
    // Obtenir tous les semestres distincts présents dans la table `note`
    $semestres = DB::table('notes')->distinct()->pluck('SEMESTRE');
    $typesMatieres = DB::table('matieres')->distinct()->pluck('TYPEMAT');
    $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
    
    // dd($option);
    // $eleves = Eleve::whereIn('CODECLAS', $classeSelectionne)
    //     ->with(['notes' => function ($query) use ($periode) {
    //         $query->where('SEMESTRE', $periode); 
    //     }])->get();
    foreach ($semestres as $semestre) {
      // Obtenir toutes les classes distinctes
      $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
      
      foreach ($classes as $classe) {
        $eleves = DB::table('eleve')->where('CODECLAS', $classe)->get();
        
        // Calcul de la moyenne pour chaque élève de la classe dans le semestre actuel
        foreach ($eleves as $eleve) {
          $notes = DB::table('notes')
          ->where('MATRICULE', $eleve->MATRICULE)
          ->where('SEMESTRE', $semestre)
          ->get();
          
          // Initialiser les variables de calcul
          $totalCoef = 0;
          $totalMSCoef = 0;
          
          foreach ($notes as $note) {
            if (isset($option['annuler_matiere'])) {
              if (is_null($note->DEV1) && is_null($note->DEV2) && is_null($note->DEV3)) {
                continue;
              }
            }
            if(isset($option['note_test'])){
              if ($note->COEF == -1) {
                if ($bonificationType == 'integral') {
                  if ($note->MS > 10) {
                    $adjustedMS = $note->MS - 10;
                    $totalMSCoef += $adjustedMS;
                  }
                } elseif ($bonificationType == 'Aucun') {
                  continue;
                } elseif ($bonificationType == 'intervalle') {
                  foreach ($bonifications as $bonification) {
                    $start = $bonification['start'];
                    $end = $bonification['end'];
                    $bonusNote = $bonification['note']; // Utilisé si nécessaire pour ajustements
                    
                    // Vérifier si la note est dans l'intervalle
                    if ($note->MS >= $start && $note->MS <= $end) {
                      $totalMSCoef += $note->MS; // Ajouter la note au total
                      break; // On sort de la boucle dès qu'une correspondance est trouvée
                    }
                  }
                }
              } elseif ($note->MS >=0) {
                $totalMSCoef += $note->MS * $note->COEF;
                $totalCoef += $note->COEF;
              }
            } else {
              if ($note->COEF == -1) {
                if ($bonificationType == 'integral') {
                  if ($note->MS1 > 10) {
                    $adjustedMS = $note->MS1 - 10;
                    $totalMSCoef += $adjustedMS;
                  }
                } elseif ($bonificationType == 'Aucun') {
                  continue;
                } elseif ($bonificationType == 'intervalle') {
                  foreach ($bonifications as $bonification) {
                    $start = $bonification['start'];
                    $end = $bonification['end'];
                    $bonusNote = $bonification['note']; // Utilisé si nécessaire pour ajustements
                    
                    // Vérifier si la note est dans l'intervalle
                    if ($note->MS1 >= $start && $note->MS1 <= $end) {
                      $totalMSCoef += $note->MS1; // Ajouter la note au total
                      break; // On sort de la boucle dès qu'une correspondance est trouvée
                    }
                  }
                }
              } elseif ($note->MS1 >=0) {
                $totalMSCoef += $note->MS1 * $note->COEF;
                $totalCoef += $note->COEF;
              }
            }
          }
          
          if (isset($option['note_conduite'])) {
            $conduiteColumn = 'NoteConduite' . $semestre;
            if (Schema::hasColumn('eleve', $conduiteColumn) && !is_null($eleve->$conduiteColumn)) {
              $totalMSCoef += $eleve->$conduiteColumn;
              $totalCoef += 1;
            }
          }
          
          
          
          if ($totalCoef > 0) {
            $moyenne = $totalMSCoef / $totalCoef;
            $column = 'MS' . $semestre;
            if (Schema::hasColumn('eleve', $column)) {
              DB::table('eleve')
              ->where('MATRICULE', $eleve->MATRICULE)
              ->update([$column => $moyenne]);
            }
            $columgene = 'TotalGene' . $semestre;
            $columcoef = 'TotalCoef' . $semestre;
            
            if (Schema::hasColumn('eleve', $columgene)) {
              DB::table('eleve')
              ->where('MATRICULE', $eleve->MATRICULE)
              ->update([$columgene => $totalMSCoef]);
            }
            if (Schema::hasColumn('eleve', $columcoef)) {
              DB::table('eleve')
              ->where('MATRICULE', $eleve->MATRICULE)
              ->update([$columcoef => $totalCoef]);
            }
          }
        }
        
        // Récupérer les élèves avec leur moyenne pour le classement
        $elevesClasse = DB::table('eleve')
        ->where('CODECLAS', $classe)
        ->whereNotNull('MS' . $semestre)
        ->orderByDesc('MS' . $semestre)
        ->get(['MATRICULE', 'MS' . $semestre]);
        
        $rang = 1;
        $lastMoyenne = null;
        $identicalRank = 0;
        
        foreach ($elevesClasse as $index => $eleveClasse) {
          $moyenne = $eleveClasse->{'MS' . $semestre};
          
          if ($moyenne === $lastMoyenne) {
            $identicalRank++; // Incrémenter pour les moyennes identiques
          } else {
            $rang += $identicalRank; // Passer au rang suivant après les égalités
            $identicalRank = 1;
          }
          
          // Mise à jour du rang dans la colonne appropriée
          $rangColumn = 'RANG' . $semestre;
          if (Schema::hasColumn('eleve', $rangColumn)) {
            DB::table('eleve')
            ->where('MATRICULE', $eleveClasse->MATRICULE)
            ->update([$rangColumn => $rang]);
          }
          
          $lastMoyenne = $moyenne;
        }
      }
      
      
      foreach ($typesMatieres as $type) {
        // Récupérer les matières de ce type
        $codesMatieres = DB::table('matieres')
        ->where('TYPEMAT', $type)
        ->pluck('CODEMAT');
        
        // Obtenir tous les élèves
        $eleves = DB::table('eleve')->get();
        
        foreach ($eleves as $eleve) {
          // Récupérer les notes de l'élève pour les matières de ce type et pour le semestre actuel
          $notes = DB::table('notes')
          ->whereIn('CODEMAT', $codesMatieres)
          ->where('MATRICULE', $eleve->MATRICULE)
          ->where('SEMESTRE', $semestre)
          ->get();
          
          // Initialiser les variables de calcul
          $totalCoef = 0;
          $totalMSCoef = 0;
          
          foreach ($notes as $note) {
            // Ajouter MS * COEF au total si la note est valide
            if ($note->MS !== null) {
              $totalMSCoef += $note->MS * $note->COEF;
              $totalCoef += $note->COEF;
            }
          }
          
          if ($totalCoef > 0) {
            $moyenne = $totalMSCoef / $totalCoef;
            
            $column = '';
            switch ($type) {
              case 1:
                $column = 'MBILANL' . $semestre; // Littéraire
                break;
                case 2:
                  $column = 'MBILANS' . $semestre; // Scientifique
                  break;
                  case 3:
                    $column = 'MoyMatFond' . $semestre; // Technique
                    break;
                  }
                  
                  if (Schema::hasColumn('eleve', $column)) {
                    DB::table('eleve')
                    ->where('MATRICULE', $eleve->MATRICULE)
                    ->update([$column => $moyenne]);
                  }
                }
              }
            }
            foreach ($classes as $classe) {
              // Filtrer les moyennes de la classe pour le semestre actuel
              $colonneMoyenne = 'MS' . $semestre;
              $moyennesClasse = DB::table('eleve')
              ->where('CODECLAS', $classe)
              ->whereNotNull($colonneMoyenne)
              ->pluck($colonneMoyenne);
              
              // Vérifier que des moyennes existent pour cette classe et ce semestre
              if ($moyennesClasse->isNotEmpty()) {
                // Calculer la moyenne la plus forte et la plus faible
                $moyenneForte = $moyennesClasse->max();
                $moyenneFaible = $moyennesClasse->min();
                
                // Déterminer les colonnes de la table `classe` à mettre à jour
                $colonneMforte = 'MFoRTE' . $semestre;
                $colonneMfaible = 'MFaIBLE' . $semestre;
                
                // Mettre à jour la table `classe` avec les moyennes forte et faible
                DB::table('classes')
                ->where('CODECLAS', $classe)
                ->update([
                  $colonneMforte => $moyenneForte,
                  $colonneMfaible => $moyenneFaible,
                ]);
              }
              
              $elevesClasse = DB::table('eleve')
              ->where('CODECLAS', $classe)
              ->where('MS' . $semestre, '>', 0)
              ->get(['MS' . $semestre]);
              
              // Calculer la somme des moyennes et le nombre d'élèves
              $totalMS = 0;
              $count = 0;
              
              foreach ($elevesClasse as $eleve) {
                $totalMS += $eleve->{'MS' . $semestre};
                $count++;
              }
              
              // Calculer la moyenne de classe si l'effectif est supérieur à 0
              if ($count > 0) {
                $moyenneClasse = $totalMS / $count;
                
                // Déterminer la colonne de la table `classe` à mettre à jour
                $moyenneClasseColumn = 'MCLASSE' . $semestre;
                
                if (Schema::hasColumn('classes', $moyenneClasseColumn)) {
                  DB::table('classes')
                  ->where('CODECLAS', $classe)
                  ->update([$moyenneClasseColumn => $moyenneClasse]);
                }
              }
            }
            
          }
          //fin calcul moy
          // dd($msgEnBasBulletin);
          
          // Récupérer les élèves dans les classes sélectionnées
          $eleves = Eleve::whereIn('CODECLAS', $classeSelectionne)
          ->with(['notes' => function ($query) use ($periode) {
            $query->where('SEMESTRE', $periode); 
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
            $infoClasse = Classes::where('CODECLAS', $eleve->CODECLAS)->first();
            $resultatEleve = [
              'nom' => $eleve->NOM,
              'prenom' => $eleve->PRENOM,
              'moyenne_semestrielle_1' => $eleve->MS1,
              'rang_1' => $eleve->RANG1,
              'moyenne_bilan_litteraire_1' => $eleve->MBILANL1,
              'moyenne_bilan_scientifique_1' => $eleve->MBILANS1,
              'moyenne_bilan_fondamentale_1' => $eleve->MoyMatFond1,
              'total_notes_1' => $eleve->TotalGen1,
              'total_coefficie_1' => $eleve->TotalCoef1,
              'redoublant' => $eleve->STATUT,
              'aptitute_sport' => $eleve->APTE,
              'matricule' => $eleve->MATRICULE,
              'anneScolaire' => $annescolaire,
              'periode' => $periode,
              'classe' => $infoClasse->CODECLAS,
              'moyenne_classe_1' => $infoClasse->MCLASSE1,
              'moyenne_faible_1' => $infoClasse->MFaIBLE1,
              'moyenne_forte_1' => $infoClasse->MFoRTE1,
              'effectif' => $effectifsParClasse[$eleve->CODECLAS] ?? 0,
              'matieres' => []
            ];
            
            // Grouper les notes par matière
            $notesParMatiere = $eleve->notes->groupBy('CODEMAT');
            
            foreach ($notesParMatiere as $codeMatiere => $notes) {
              // Vérifier si la matière est la conduite
              if ($codeMatiere == $conduite ) {
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
                
                $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                  'eleve_id' => $eleve->MATRICULE,
                  'moyenne' => $noteSpeciale
                ];
                
                // Assigner les informations de la matière avec la note récupérée
                $mentionMatSpecial = $this->determineMention($noteSpeciale, $params2);
                
                $resultatEleve['matieres'][] = [
                  'code_matiere' => $codeMatiere,
                  'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? ($codeMatiere == $conduite ? 'Conduite' : 'EPS'),
                  'coefficient' => $notes->first()->COEF,
                  'moyenne_sur_20' => $noteSpeciale,
                  'test' => $notes->first()->TEST,
                  'moyenne_coeff' => $noteSpeciale * ($notes->first()->COEF),
                  'mentionProf' => $mentionMatSpecial, // Pas de mention pour la conduite ou EPS
                  'Typematiere' => 'CONDUITE', // Indication que c'est une matière conduite
                ];
                continue; // Passer à la matière suivante
              }
              
              // Vérification pour la matières EPS 
              
              if ($codeMatiere == $eps) {
                // Calcul de la moyenne des interrogations valides
                $interrosValides = $notes->map(function ($note) {
                  return collect([$note->INT1, $note->INT2, $note->INT3])
                  ->filter(function ($value) {
                    return $value !== null && $value != 21 && $value != 0;
                  });
                })->flatten();
                
                $moyenneInterros = $interrosValides->avg();
                
                // Calcul de la moyenne des devoirs valides
                $devoirsValides = $notes->map(function ($note) {
                  return collect([$note->DEV1, $note->DEV2, $note->DEV3])
                  ->filter(function ($value) {
                    return $value !== null && $value != 21 && $value != 0;
                  });
                })->flatten();
                
                $moyenneDevoirs = $devoirsValides->avg();
                
                // Calcul de la moyenne de la matière
                if ($moyenneInterros !== null && $moyenneDevoirs !== null) {
                  $moyenneEps = ($moyenneInterros + $moyenneDevoirs) / 2;
                } elseif ($moyenneInterros !== null) {
                  $moyenneEps = $moyenneInterros;
                } elseif ($moyenneDevoirs !== null) {
                  $moyenneEps = $moyenneDevoirs;
                } else {
                  $moyenneEps = null; // Aucun note valide
                }
                
                $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                  'eleve_id' => $eleve->MATRICULE,
                  'moyenne' => $moyenneEps
                ];
                
                // Déterminer la mention pour la matière
                $mentionMaEps = $this->determineMention($moyenneEps, $params2);
                
                // Extraire les notes de DEV1, DEV2, DEV3 individuellement
                $noteDEV1 = $notes->first()->DEV1 ?? null;
                $noteDEV2 = $notes->first()->DEV2 ?? null;
                $noteDEV3 = $notes->first()->DEV3 ?? null;
                
                // Ajouter cette matière au résultat avec les informations de bonification et les notes individuelles
                $resultatEleve['matieres'][] = [
                  'code_matiere' => $codeMatiere,
                  'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? 'EPS',
                  'moyenne_interro' => $moyenneInterros,
                  'devoir1' => $noteDEV1, // Note individuelle DEV1
                  'devoir2' => $noteDEV2, // Note individuelle DEV2
                  'devoir3' => $noteDEV3, // Note individuelle DEV3
                  'test' => $notes->first()->TEST,
                  'coefficient' => $notes->first()->COEF,
                  'moyenne_sur_20' => $moyenneEps,
                  'moyenne_coeff' => $moyenneEps * ($notes->first()->COEF),
                  'mentionProf' => $mentionMaEps,
                  'Typematiere' => 'EPS', // Indication que c'est une matière eps
                ];
                
                continue; // Passer à la matière suivante
              }
              
              // Vérification pour les matières bonifiées (coefficient = -1)
              if ($notes->first()->COEF == -1) {
                // Calcul de la moyenne des interrogations valides
                $interrosValides = $notes->map(function ($note) {
                  return collect([$note->INT1, $note->INT2, $note->INT3])
                  ->filter(function ($value) {
                    return $value !== null && $value != 21 && $value != 0;
                  });
                })->flatten();
                
                $moyenneInterros = $interrosValides->avg();
                
                // Calcul de la moyenne des devoirs valides
                $devoirsValides = $notes->map(function ($note) {
                  return collect([$note->DEV1, $note->DEV2, $note->DEV3])
                  ->filter(function ($value) {
                    return $value !== null && $value != 21 && $value != 0;
                  });
                })->flatten();
                
                $moyenneDevoirs = $devoirsValides->avg();
                
                // Calcul de la moyenne de la matière bonifiée
                if ($moyenneInterros !== null && $moyenneDevoirs !== null) {
                  $moyenneBonifiee = ($moyenneInterros + $moyenneDevoirs) / 2;
                } elseif ($moyenneInterros !== null) {
                  $moyenneBonifiee = $moyenneInterros;
                } elseif ($moyenneDevoirs !== null) {
                  $moyenneBonifiee = $moyenneDevoirs;
                } else {
                  $moyenneBonifiee = null; // Aucun note valide
                }
                
                $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                  'eleve_id' => $eleve->MATRICULE,
                  'moyenne' => $moyenneBonifiee
                ];
                
                // Déterminer la moyenne_intervalle en fonction des bonifications
                $moyenneIntervalle = null;
                foreach ($bonifications as $bonification) {
                  if ($moyenneBonifiee >= $bonification['start'] && $moyenneBonifiee < $bonification['end']) {
                    $moyenneIntervalle = $bonification['note'];
                    break; // On arrête la boucle dès qu'on trouve le bon intervalle
                  }
                }
                
                // Déterminer la mention pour la matière
                $mentionMaBonifier = $this->determineMention($moyenneBonifiee, $params2);
                
                // Extraire les notes de DEV1, DEV2, DEV3 individuellement
                $noteDEV1 = $notes->first()->DEV1 ?? null;
                $noteDEV2 = $notes->first()->DEV2 ?? null;
                $noteDEV3 = $notes->first()->DEV3 ?? null;
                
                
                // Ajouter cette matière au résultat en tant que matière bonifiée avec notes individuelles
                $resultatEleve['matieres'][] = [
                  'code_matiere' => $codeMatiere,
                  'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? 'Matière Bonifiée',
                  'coefficient' => $notes->first()->COEF,
                  'moyenne_interro' => $moyenneInterros,
                  'devoir1' => $noteDEV1, // Note individuelle DEV1
                  'devoir2' => $noteDEV2, // Note individuelle DEV2
                  'devoir3' => $noteDEV3, // Note individuelle DEV3
                  'test' => $notes->first()->TEST,
                  'moyenne_sur_20' => $moyenneBonifiee,
                  'moyenne_intervalle' => intval($moyenneIntervalle),
                  'moyenne_coeff' => $moyenneBonifiee * (-1 * $notes->first()->COEF),
                  'surplus' => $moyenneBonifiee - 10,
                  'mentionProf' => $mentionMaBonifier,
                  'Typematiere' => 'Matière_Bonifiée', // Indication que c'est une matière bonifiée
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
                'Typematiere' => 'Normal', // Indication que c'est une matière normale
                
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
          
          return view('pages.notes.printbulletindenotes', compact('request', 'resultats', 'eleves', 'option', 'entete', 'typean'));
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
      