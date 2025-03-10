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
use App\Models\Matieres;
use App\Models\Notes;
use App\Models\Clasmat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Duplicatafacture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Roundcube\Rtf\Html; // ou la classe appropriée selon la documentation du package
// use RtfHtmlPhp\Document;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotesExport;


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
    $params2 = Params2::first();
    $typean = $params2->TYPEAN;
    return view('pages.notes.bulletindenotes', compact('classes', 'typeenseigne', 'promotions', 'eleves', 'matieres', 'typean'));
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


  // private function calculerMoyenne($request) {

  //   $option = Session::get('option');
  //   // dd($request);
  //   $moyennesParClasseEtMatiere = [];
  //   $paramselection = $request['paramselection'];
  //   $bonificationType = $request['bonificationType'];
  //   $bonifications = $request['bonification'];
  //   $conduite = $request['conduite']; // Code de la matière pour la conduite
  //   $msgEnBasBulletin = $request['msgEnBasBulletin'];
  //   $periode = $request['periode'];
  //   $eps = $request['eps'];
  //   $nbabsence = $request['nbabsence'];
  //   // $apartirde = $request['apartirde'];
  //   $classeSelectionne = $request['selected_classes'];
  //   // dd($classeSelectionne);
    
  //   $params2 = Params2::first();
  //   $typean = $params2->TYPEAN;
  //   $rtfContent = Params2::first()->EnteteBull;
  //   $entete = $this->extractTextFromRtf($rtfContent);
    
  //   $infoparamcontrat = Paramcontrat::first();
  //   $anneencours = $infoparamcontrat->anneencours_paramcontrat;
  //   $annesuivante = $anneencours + 1;
  //   $annescolaire = $anneencours . '-' . $annesuivante;
    
  //   // Filtrer le tableau en enlevant l'élément 'all'
  //   $classeSelectionne = array_filter($classeSelectionne, function ($value) {
  //     return $value !== 'all';
  //   });
  //   $clases = DB::table('eleve')->select('CODECLAS')->distinct()->get();
    


  //   foreach ($clases as $classe) {
  //     // Récupérer les élèves d'une classe triés par MAN (moyenne annuelle)
  //     $eleves = DB::table('eleve')
  //     ->where('CODECLAS', $classe->CODECLAS)
  //     ->whereNotNull('MAN')
  //     ->orderBy('MAN', 'desc')
  //     ->get();
      
  //     $rank = 1; // Rang initial
  //     $previousMan = null; // Pour vérifier les égalités
      
  //     foreach ($eleves as $eleve) {
  //       if ($previousMan !== null && $eleve->MAN !== $previousMan) {
  //         $rank++; // Incrément du rang seulement si la moyenne est différente
  //       }
        
  //       // Mise à jour du rang annuel
  //       DB::table('eleve')
  //       ->where('MATRICULE', $eleve->MATRICULE)
  //       ->update(['RANGA' => $rank]);
        
  //       $previousMan = $eleve->MAN; // Stocker la moyenne pour comparaison
  //     }
  //   }
  //   $eles = DB::table('eleve')->get();
    
  //   foreach ($eles as $eleve) {
  //     $notes = [];
  //     $notes = [];
  //     for ($i = 1; $i <= 12; $i++) {
  //         $ms = $eleve->{'MS' . $i}; // Accéder dynamiquement aux colonnes MS1, MS2, ...
  //         if ($ms !== null && $ms != -1) {
  //             $notes[] = $ms;
  //         }
  //     }
  //     if (!empty($notes)) {
  //       $man = array_sum($notes) / count($notes);
  //       DB::table('eleve')
  //       ->where('MATRICULE', $eleve->MATRICULE)
  //       ->update(['MAN' => $man]);
  //     }
  //   }
  //   // Traite chaque intervalle de bonification
  //   foreach ($bonifications as $bonification) {
  //     $start = $bonification['start'];
  //     $end = $bonification['end'];
  //     $note = $bonification['note'];
  //     // Code pour traiter les bonifications si nécessaire
  //   }
  //   ///Calculere moyenne
  //   // Obtenir tous les semestres distincts présents dans la table note
  //   $semestres = DB::table('notes')->distinct()->pluck('SEMESTRE');
  //   $typesMatieres = DB::table('matieres')->distinct()->pluck('TYPEMAT');
  //   $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
    
  //   // dd($option);
  //   $eleves = Eleve::whereIn('CODECLAS', $classeSelectionne)
  //       ->with(['notes' => function ($query) use ($periode) {
  //           $query->where('SEMESTRE', $periode); 
  //       }])->get();
  //   foreach ($semestres as $semestre) {
  //     // Obtenir toutes les classes distinctes
  //     $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
      
  //     foreach ($classes as $classe) {
  //       $eleves = DB::table('eleve')->where('CODECLAS', $classe)->get();
        
  //       // Calcul de la moyenne pour chaque élève de la classe dans le semestre actuel
  //       foreach ($eleves as $eleve) {
  //         $notes = DB::table('notes')
  //         ->where('MATRICULE', $eleve->MATRICULE)
  //         ->where('SEMESTRE', $semestre)
  //         ->get();
          
  //         // Initialiser les variables de calcul
  //         $totalCoef = 0;
  //         $totalMSCoef = 0;
          
  //         foreach ($notes as $note) {
  //           if (isset($option['annuler_matiere'])) {
  //             if (is_null($note->DEV1) && is_null($note->DEV2) && is_null($note->DEV3)) {
  //               continue;
  //             }
  //           }
  //           if(isset($option['note_test'])){
  //             if ($note->COEF == -1) {
  //               if ($bonificationType == 'integral') {
  //                 if ($note->MS > 10) {
  //                   $adjustedMS = $note->MS - 10;
  //                   $totalMSCoef += $adjustedMS;
  //                 }
  //               } elseif ($bonificationType == 'Aucun') {
  //                 continue;
  //               } elseif ($bonificationType == 'intervalle') {
  //                 foreach ($bonifications as $bonification) {
  //                   $start = $bonification['start'];
  //                   $end = $bonification['end'];
  //                   $bonusNote = $bonification['note']; // Utilisé si nécessaire pour ajustements
                    
  //                   // Vérifier si la note est dans l'intervalle
  //                   if ($note->MS >= $start && $note->MS <= $end) {
  //                     $totalMSCoef += $note->MS; // Ajouter la note au total
  //                     break; // On sort de la boucle dès qu'une correspondance est trouvée
  //                   }
  //                 }
  //               }
  //             } elseif ($note->MS >=0) {
  //               $totalMSCoef += $note->MS * $note->COEF;
  //               $totalCoef += $note->COEF;
  //             }
  //           } else {
  //             if ($note->COEF == -1) {
  //               if ($bonificationType == 'integral') {
  //                 if ($note->MS1 > 10) {
  //                   $adjustedMS = $note->MS1 - 10;
  //                   $totalMSCoef += $adjustedMS;
  //                 }
  //               } elseif ($bonificationType == 'Aucun') {
  //                 continue;
  //               } elseif ($bonificationType == 'intervalle') {
  //                 foreach ($bonifications as $bonification) {
  //                   $start = $bonification['start'];
  //                   $end = $bonification['end'];
  //                   $bonusNote = $bonification['note']; // Utilisé si nécessaire pour ajustements
                    
  //                   // Vérifier si la note est dans l'intervalle
  //                   if ($note->MS1 >= $start && $note->MS1 <= $end) {
  //                     $totalMSCoef += $note->MS1; // Ajouter la note au total
  //                     break; // On sort de la boucle dès qu'une correspondance est trouvée
  //                   }
  //                 }
  //               }
  //             } elseif ($note->MS1 >=0) {
  //               $totalMSCoef += $note->MS1 * $note->COEF;
  //               $totalCoef += $note->COEF;
  //             }
  //           }
  //         }
          
  //         if (isset($option['note_conduite'])) {
  //           $conduiteColumn = 'NoteConduite' . $semestre;
  //           if (Schema::hasColumn('eleve', $conduiteColumn) && !is_null($eleve->$conduiteColumn)) {
  //             $totalMSCoef += $eleve->$conduiteColumn;
  //             $totalCoef += 1;
  //           }
  //         }
          
          
          
  //         if ($totalCoef > 0) {
  //           $moyenne = $totalMSCoef / $totalCoef;
  //           $column = 'MS' . $semestre;
  //           if (Schema::hasColumn('eleve', $column)) {
  //             DB::table('eleve')
  //             ->where('MATRICULE', $eleve->MATRICULE)
  //             ->update([$column => $moyenne]);
  //           }
  //           $columgene = 'TotalGene' . $semestre;
  //           $columcoef = 'TotalCoef' . $semestre;
            
  //           if (Schema::hasColumn('eleve', $columgene)) {
  //             DB::table('eleve')
  //             ->where('MATRICULE', $eleve->MATRICULE)
  //             ->update([$columgene => $totalMSCoef]);
  //           }
  //           if (Schema::hasColumn('eleve', $columcoef)) {
  //             DB::table('eleve')
  //             ->where('MATRICULE', $eleve->MATRICULE)
  //             ->update([$columcoef => $totalCoef]);
  //           }
  //         }
  //       }
        
  //       // Récupérer les élèves avec leur moyenne pour le classement
  //       $elevesClasse = DB::table('eleve')
  //       ->where('CODECLAS', $classe)
  //       ->whereNotNull('MS' . $semestre)
  //       ->orderByDesc('MS' . $semestre)
  //       ->get(['MATRICULE', 'MS' . $semestre]);
        
  //       $rang = 1;
  //       $lastMoyenne = null;
  //       $identicalRank = 0;
        
  //       foreach ($elevesClasse as $index => $eleveClasse) {
  //         $moyenne = $eleveClasse->{'MS' . $semestre};
          
  //         if ($moyenne === $lastMoyenne) {
  //           $identicalRank++; // Incrémenter pour les moyennes identiques
  //         } else {
  //           $rang += $identicalRank; // Passer au rang suivant après les égalités
  //           $identicalRank = 1;
  //         }
          
  //         // Mise à jour du rang dans la colonne appropriée
  //         $rangColumn = 'RANG' . $semestre;
  //         if (Schema::hasColumn('eleve', $rangColumn)) {
  //           DB::table('eleve')
  //           ->where('MATRICULE', $eleveClasse->MATRICULE)
  //           ->update([$rangColumn => $rang]);
  //         }
          
  //         $lastMoyenne = $moyenne;
  //       }
  //     }
      
      
  //     foreach ($typesMatieres as $type) {
  //       // Récupérer les matières de ce type
  //       $codesMatieres = DB::table('matieres')
  //       ->where('TYPEMAT', $type)
  //       ->pluck('CODEMAT');
        
  //       // Obtenir tous les élèves
  //       $elevesS = DB::table('eleve')->get();
        
  //       foreach ($elevesS as $eleve) {
  //         // Récupérer les notes de l'élève pour les matières de ce type et pour le semestre actuel
  //         $notes = DB::table('notes')
  //         ->whereIn('CODEMAT', $codesMatieres)
  //         ->where('MATRICULE', $eleve->MATRICULE)
  //         ->where('SEMESTRE', $semestre)
  //         ->get();
          
  //         // Initialiser les variables de calcul
  //         $totalCoef = 0;
  //         $totalMSCoef = 0;
          
  //         foreach ($notes as $note) {
  //           // Ajouter MS * COEF au total si la note est valide
  //           if ($note->MS !== null) {
  //             $totalMSCoef += $note->MS * $note->COEF;
  //             $totalCoef += $note->COEF;
  //           }
  //         }
          
  //         if ($totalCoef > 0) {
  //           $moyenne = $totalMSCoef / $totalCoef;
            
  //           $column = '';
  //           switch ($type) {
  //             case 1:
  //               $column = 'MBILANL' . $semestre; // Littéraire
  //               break;
  //               case 2:
  //                 $column = 'MBILANS' . $semestre; // Scientifique
  //                 break;
  //                 case 3:
  //                   $column = 'MoyMatFond' . $semestre; // Technique
  //                   break;
  //                 }
                  
  //                 if (Schema::hasColumn('eleve', $column)) {
  //                   DB::table('eleve')
  //                   ->where('MATRICULE', $eleve->MATRICULE)
  //                   ->update([$column => $moyenne]);
  //                 }
  //               }
  //             }
  //           }
  //           foreach ($classes as $classe) {
  //             // Filtrer les moyennes de la classe pour le semestre actuel
  //             $colonneMoyenne = 'MS' . $semestre;
  //             $moyennesClasse = DB::table('eleve')
  //             ->where('CODECLAS', $classe)
  //             ->whereNotNull($colonneMoyenne)
  //             ->pluck($colonneMoyenne);
              
  //             // Vérifier que des moyennes existent pour cette classe et ce semestre
  //             if ($moyennesClasse->isNotEmpty()) {
  //               // Calculer la moyenne la plus forte et la plus faible
  //               $moyenneForte = $moyennesClasse->max();
  //               $moyenneFaible = $moyennesClasse->min();
                
  //               // Déterminer les colonnes de la table classe à mettre à jour
  //               $colonneMforte = 'MFoRTE' . $semestre;
  //               $colonneMfaible = 'MFaIBLE' . $semestre;
                
  //               // Mettre à jour la table classe avec les moyennes forte et faible
  //               DB::table('classes')
  //               ->where('CODECLAS', $classe)
  //               ->update([
  //                 $colonneMforte => $moyenneForte,
  //                 $colonneMfaible => $moyenneFaible,
  //               ]);
  //             }
              
  //             $elevesClasse = DB::table('eleve')
  //             ->where('CODECLAS', $classe)
  //             ->where('MS' . $semestre, '>', 0)
  //             ->get(['MS' . $semestre]);
              
  //             // Calculer la somme des moyennes et le nombre d'élèves
  //             $totalMS = 0;
  //             $count = 0;
              
  //             foreach ($elevesClasse as $eleve) {
  //               $totalMS += $eleve->{'MS' . $semestre};
  //               $count++;
  //             }
              
  //             // Calculer la moyenne de classe si l'effectif est supérieur à 0
  //             if ($count > 0) {
  //               $moyenneClasse = $totalMS / $count;
                
  //               // Déterminer la colonne de la table classe à mettre à jour
  //               $moyenneClasseColumn = 'MCLASSE' . $semestre;
                
  //               if (Schema::hasColumn('classes', $moyenneClasseColumn)) {
  //                 DB::table('classes')
  //                 ->where('CODECLAS', $classe)
  //                 ->update([$moyenneClasseColumn => $moyenneClasse]);
  //               }
  //             }
  //           }
            
  //         }
  // }



  private function initialiserVariables($request)
{

  $option = Session::get('option');

  // dd($request);
    $this->periode = $request['periode'];
    $this->classesS = $request['selected_classes'];
    $this->bonifications = $request['bonification'];
    $this->bonificationType = $request['bonificationType'];
    // $this->option = $request['option'];
    // $this->annee = Parametre::getValeur('annee');
    $infoparamcontrat = Paramcontrat::first();
    $this->annee = $infoparamcontrat->anneencours_paramcontrat;
    // $this->entete = Parametre::getValeur('entete');
    $params2 = Params2::first();
    $typean = $params2->TYPEAN;
    $rtfContent = Params2::first()->EnteteBull;
    // $document = new Document($rtfContent);
    // $formatter = new HtmlFormatter();
    // $this->entete = $formatter->Format($rtfContent);
    // dd($this->entete);
    // $this->entete = $document->toHtml();
    // $this->entete = $rtfContent;    
    // $this->entete = Html::convert($rtfContent);    
    $this->entete = $this->extractTextFromRtf($rtfContent);
    // $this->totalSemestres = Parametre::getValeur('total_semestres');
    $this->classes = DB::table('eleve')->select('CODECLAS')->distinct()->get();

}

private function mettreAJourRangAnnuel()
{
    foreach ($this->classes as $classe) {
        $eleves = DB::table('eleve')
            ->where('CODECLAS', $classe->CODECLAS)
            ->orderByDesc('MAN', 'desc')
            ->get();

        $rang = 1;
        $precedentMAN = null;

        foreach ($eleves as $eleve) {
            $currentMAN = $eleve->MAN;
            if ($precedentMAN !== null && $currentMAN != $precedentMAN) {
                $rang++;
            }
            DB::table('eleve')
                ->where('MATRICULE', $eleve->MATRICULE)
                ->update(['RANGA' => $rang]);
            $precedentMAN = $currentMAN;
        }
    }
}

private function calculerMoyennesAnnuelles()
{
    foreach ($this->classes as $classe) {
        $eleves = DB::table('eleve')
            // ->where('Classe', $classe)
            ->get();

        foreach ($eleves as $eleve) {
            $moyennes = [];
            for ($i = 1; $i <= 12; $i++) {
                $colonne = 'MS' . $i;
                if (Schema::hasColumn('eleve', $colonne)) {
                    $moyenne = $eleve->$colonne;
                    if ($moyenne !== null &&  $moyenne > 0) {
                        $moyennes[] = $moyenne;
                    }
                }
            }

            if (count($moyennes) > 0) {
                $moyenneAnnuelle = array_sum($moyennes) / count($moyennes);
                DB::table('eleve')
                    ->where('MATRICULE', $eleve->MATRICULE)
                    ->update(['MAN' => $moyenneAnnuelle]);
            }
        }
    }
}

// ********************************
private function appliquerBonifications()
{
    foreach ($this->classes as $classe) {
        $eleves = DB::table('eleve')
            ->where('Classe', $classe)
            ->get();

        foreach ($eleves as $eleve) {
            $moyenneAnnuelle = $eleve->MAN;

            switch ($this->bonifications['type']) {
                case 'aucune':
                    break;

                case 'integral':
                    $moyenneAnnuelle += $this->bonifications['valeur'];
                    break;

                case 'intervalle':
                    if ($moyenneAnnuelle >= $this->bonifications['min'] && $moyenneAnnuelle <= $this->bonifications['max']) {
                        $moyenneAnnuelle += $this->bonifications['valeur'];
                    }
                    break;
            }

            DB::table('eleve')
                ->where('Matricule', $eleve->Matricule)
                ->update(['MAN' => $moyenneAnnuelle]);
        }
    }
}


private function calculerMoyennesSemestrielles($periode)
{
    foreach ($this->classes as $classe) {
        $eleves = DB::table('eleve')
            ->where('Classe', $classe)
            ->get();

        foreach ($eleves as $eleve) {
            $notes = DB::table('notes')
                ->where('Matricule', $eleve->Matricule)
                ->where('Semestre', $periode)
                ->get();

            $totalNotes = 0;
            $totalCoef = 0;

            foreach ($notes as $note) {
                $totalNotes += $note->Note * $note->Coef;
                $totalCoef += $note->Coef;
            }

            if ($this->option['inclure_conduite']) {
                $totalNotes += $eleve->Conduite * $this->option['coef_conduite'];
                $totalCoef += $this->option['coef_conduite'];
            }

            if ($totalCoef > 0) {
                $moyenneSemestrielle = $totalNotes / $totalCoef;
                DB::table('eleve')
                    ->where('Matricule', $eleve->Matricule)
                    ->update(['MS' . $periode => $moyenneSemestrielle]);
            }
        }
    }
}

private function attribuerRangsSemestriels()
{
    foreach ($this->classes as $classe) {
        $eleves = DB::table('eleve')
            ->where('Classe', $classe)
            ->orderByDesc('MS' . $this->periode)
            ->get();

        $rang = 1;
        $precedenteMoyenne = null;

        foreach ($eleves as $eleve) {
            $currentMoyenne = $eleve->{'MS' . $this->periode};

            if ($precedenteMoyenne !== null && $currentMoyenne != $precedenteMoyenne) {
                $rang++;
            }

            DB::table('eleve')
                ->where('Matricule', $eleve->Matricule)
                ->update(['RANG' . $this->periode => $rang]);

            $precedenteMoyenne = $currentMoyenne;
        }
    }
}

public function calculerMoyenne($request)
{
    $this->initialiserVariables($request);
    $this->mettreAJourRangAnnuel();
    $this->calculerMoyennesAnnuelles();
    $this->appliquerBonifications();
    $this->calculerMoyennesSemestrielles($this->periode);
    $this->attribuerRangsSemestriels();
}




private function determineAppreciation($moyenne, $params2)
{
    if ($moyenne < $params2->Borne1) {
        return $params2->Mention1d;
    } elseif ($moyenne <= $params2->Borne2) {
        return $params2->Mention2d;
    } elseif ($moyenne <= $params2->Borne3) {
        return $params2->Mention3d;
    } elseif ($moyenne <= $params2->Borne4) {
        return $params2->Mention4d;
    } elseif ($moyenne <= $params2->Borne5) {
        return $params2->Mention5d;
    } elseif ($moyenne <= $params2->Borne6) {
        return $params2->Mention6d;
    } elseif ($moyenne <= $params2->Borne7) {
        return $params2->Mention7d;
    } else {
        return $params2->Mention8d;
    }
}


   /**
    * Fonction auxiliaire pour calculer les classements à partir d'un tableau associatif
    * (clé : identifiant de l'élève, valeur : moyenne)
    */
    private function computeRankings(array $averages)
    {
         // On filtre les valeurs nulles et celles égales à 21
         $filtered = array_filter($averages, function ($val) {
           return $val !== null && $val != 21;
         });
        // Tri décroissant : la meilleure moyenne en tête
        arsort($filtered);
        $rankings = [];
        $rank = 1;
        $prevValue = null;
        $counter = 0;
        foreach ($filtered as $matricule => $value) {
            $counter++;
            if ($prevValue !== null && $value < $prevValue) {
                $rank = $counter;
            }
            $rankings[$matricule] = $rank;
            $prevValue = $value;
        }
        return $rankings;
   }
     

  
  
  public function printbulletindenotes(Request $request)
  {
    // dd($request->all());
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
    $pondTrim1 = $request->input('pondTrim1', 1);
    $pondTrim2 = $request->input('pondTrim2', 1);
    $pondTrim3 = $request->input('pondTrim3', 1);

    session()->put('conduite', $conduite);
    session()->put('eps', $eps);
    session()->put('nbabsence', $nbabsence);
    // dd($pondTrim1);
    
    $params2 = Params2::first();
    $typean = $params2->TYPEAN;
    $rtfContent = Params2::first()->EnteteBull;
    // $entete = $this->extractTextFromRtf($rtfContent);
    $rtfContent = Params2::first()->EnteteBull;
    $document = new Document($rtfContent);
    $formatter = new HtmlFormatter();
    $entete = $formatter->Format($document);
    // dd($entete);
    $logo = $params2->logoimage;

    // Conversion des données en Base64
    $logoBase64 = base64_encode($logo);

    // Définir le type MIME de l'image (adaptez-le en fonction de votre image)
    $mimeType = 'image/png';


    $infoparamcontrat = Paramcontrat::first();
    $anneencours = $infoparamcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours . '-' . $annesuivante;
    
    // Filtrer le tableau en enlevant l'élément 'all'
    $classeSelectionne = array_filter($classeSelectionne, function ($value) {
      return $value !== 'all';
    });
    $clases = DB::table('eleve')->select('CODECLAS')->distinct()->get();
   
    // 



     // Récupérer la classe sélectionnée
     $codeClasse = $classeSelectionne;
     if (!$codeClasse) {
         return redirect()->back()->with('error', 'Veuillez sélectionner une classe.');
     }
 
    //  dd($classeSelectionne);

     // Récupérer le paramétrage de l'année
     $params2 = Params2::first();
     $typean = $params2->TYPEAN; // 1 : semestres (2 périodes), 2 : trimestres (3 périodes)
     $periodsA = ($typean == 1) ? [1, 2] : [1, 2, 3];
 
     // Récupérer tous les élèves de la classe sélectionnée
     $elevesA = Eleve::where('CODECLAS', $codeClasse)->get();
 
     $resultats = [];
     $annualAverages = [];      // [matricule => moyenneAnnuelle]
     $periodAverages = [];      // [periode => [matricule => moyennePourLaPeriode]]
 
     // Parcourir chaque élève
     foreach ($elevesA as $eleveA) {
         // Tableau pour stocker les infos par période pour cet élève
         $studentPeriods = [];
 
         foreach ($periodsA as $periodeA) {
             // Récupérer les enregistrements de notes de l'élève pour cette période
             $notes = Notes::where('MATRICULE', $eleveA->MATRICULE)
                 ->where('CODECLAS', $eleveA->CODECLAS)
                 ->where('SEMESTRE', $periodeA)
                 ->get();
 
             // Accumulateurs pour le calcul de la moyenne de la période (pondérée)
             $totalNote = 0;
             $totalCoef = 0;
 
             // Accumulateurs pour le calcul des bilans (arithmétiques)
             $totalNoteLitteraire = 0;
             $countNoteLitteraire = 0;
             $totalNoteScientifique = 0;
             $countNoteScientifique = 0;
             $totalNoteFondamentale = 0;
             $countNoteFondamentale = 0;
 
             foreach ($notes as $note) {
                 // Exclure les valeurs indésirables
                 if ($note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                     $totalNote += $note->MS * $note->COEF;
                     $totalCoef += $note->COEF;
                 }
 
                 // Récupérer la matière pour déterminer son type
                 $matiereA = Matieres::where('CODEMAT', $note->CODEMAT)->first();
                 if ($matiereA) {
                     // Matière littéraire : TYPEMAT == 1
                     if ($matiereA->TYPEMAT == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                         $totalNoteLitteraire += $note->MS;
                         $countNoteLitteraire++;
                     }
                     // Matière scientifique : TYPEMAT == 2
                     if ($matiereA->TYPEMAT == 2 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                         $totalNoteScientifique += $note->MS;
                         $countNoteScientifique++;
                     }
                 }
 
                 // Pour la matière fondamentale, on vérifie dans la table Clasmat
                 $classmat = Clasmat::where('CODECLAS', $eleveA->CODECLAS)
                     ->where('CODEMAT', $note->CODEMAT)
                     ->first();
                 if ($classmat && $classmat->FONDAMENTALE == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                     $totalNoteFondamentale += $note->MS;
                     $countNoteFondamentale++;
                 }
             } // fin foreach notes
 
             // Calcul de la moyenne de la période (pondérée)
             $moyennePeriode = ($totalCoef > 0) ? round($totalNote / $totalCoef, 2) : null;
             // Appréciation pour la période
             $appreciationPeriode = ($moyennePeriode !== null) ? $this->determineAppreciation($moyennePeriode, $params2) : null;
 
             // Calcul des bilans arithmétiques pour la période
             $moyenneLitteraire = ($countNoteLitteraire > 0) ? round($totalNoteLitteraire / $countNoteLitteraire, 2) : null;
             $moyenneScientifique = ($countNoteScientifique > 0) ? round($totalNoteScientifique / $countNoteScientifique, 2) : null;
             $moyenneFondamentale = ($countNoteFondamentale > 0) ? round($totalNoteFondamentale / $countNoteFondamentale, 2) : null;
 
             // Vous pouvez également ajouter une appréciation pour chaque bilan si nécessaire, par exemple :
             $appreciationLitteraire = ($moyenneLitteraire !== null) ? $this->determineAppreciation($moyenneLitteraire, $params2) : null;
             $appreciationScientifique = ($moyenneScientifique !== null) ? $this->determineAppreciation($moyenneScientifique, $params2) : null;
             $appreciationFondamentale = ($moyenneFondamentale !== null) ? $this->determineAppreciation($moyenneFondamentale, $params2) : null;
 
             // Sauvegarder les infos pour cette période
             $studentPeriods[$periodeA] = [
                 'moyenne'             => $moyennePeriode,
                 'appreciation'        => $appreciationPeriode,
                 'totalNotesCoeff'     => $totalNote,
                 'totalCoef'           => $totalCoef,
                 'bilan_litteraire'    => $moyenneLitteraire,
                 'appreciation_litteraire' => $appreciationLitteraire,
                 'bilan_scientifique'  => $moyenneScientifique,
                 'appreciation_scientifique' => $appreciationScientifique,
                 'bilan_fondamentale'  => $moyenneFondamentale,
                 'appreciation_fondamentale' => $appreciationFondamentale,
             ];
         } // fin foreach périodes
 
         // Calcul de la moyenne annuelle (moyenne arithmétique des moyennes de période)
        //  $sumPeriodAverages = 0;
        //  $countPeriodAverages = 0;
        //  foreach ($studentPeriods as $periodeA => $data) {
        //      if ($data['moyenne'] !== null) {
        //          $sumPeriodAverages += $data['moyenne'];
        //          $countPeriodAverages++;
        //      }
        //  }
        //  $moyenneAnnuelle = ($countPeriodAverages > 0) ? round($sumPeriodAverages / $countPeriodAverages, 2) : null;
        //  $appreciationAnnuelle = ($moyenneAnnuelle !== null) ? $this->determineAppreciation($moyenneAnnuelle, $params2) : null;

        // dd($pondTrim3);

        $sumWeighted = 0;
        $totalWeights = 0;
        if (isset($studentPeriods[1]) && $studentPeriods[1]['moyenne'] !== null) {
            $sumWeighted += $studentPeriods[1]['moyenne'] * $pondTrim1;
            $totalWeights += $pondTrim1;
        }
        if (isset($studentPeriods[2]) && $studentPeriods[2]['moyenne'] !== null) {
            $sumWeighted += $studentPeriods[2]['moyenne'] * $pondTrim2;
            $totalWeights += $pondTrim2;
        }
        if (isset($studentPeriods[3]) && $studentPeriods[3]['moyenne'] !== null) {
            $sumWeighted += $studentPeriods[3]['moyenne'] * $pondTrim3;
            $totalWeights += $pondTrim3;
        }
        $moyenneAnnuelle = ($totalWeights > 0) ? round($sumWeighted / $totalWeights, 2) : null;
        $appreciationAnnuelle = ($moyenneAnnuelle !== null) ? $this->determineAppreciation($moyenneAnnuelle, $params2) : null;
 
         // Stocker pour le calcul des classements
         $annualAverages[$eleveA->MATRICULE] = $moyenneAnnuelle;
         foreach ($studentPeriods as $periodeA => $data) {
             $periodAverages[$periodeA][$eleveA->MATRICULE] = $data['moyenne'];
         }
 
         // Stocker les résultats de l'élève, y compris les bilans annuels et appréciations
         $resultatsA[] = [
             'eleve'                   => $eleveA,
             'moyenneAnnuelle'         => $moyenneAnnuelle,
             'appreciationAnnuelle'    => $appreciationAnnuelle,
             // 'bilanLitteraireAnnuel'   => $studentPeriods, // Vous pouvez calculer un bilan annuel de façon similaire si besoin
             'periods'                 => $studentPeriods,
         ];
     } // fin foreach élèves
 
     // Calcul des classements (annual et par période)
     $annualRankings = $this->computeRankings($annualAverages);
     $periodRankings = [];
     foreach ($periodsA as $periodeA) {
         $periodRankings[$periodeA] = $this->computeRankings($periodAverages[$periodeA] ?? []);
     }
 
     foreach ($resultatsA as &$resA) {
         $matricule = $resA['eleve']->MATRICULE;
         $resA['rangAnnuel'] = $annualRankings[$matricule] ?? null;
         foreach ($periodsA as $periodeA) {
             $resA['periods'][$periodeA]['rang'] = $periodRankings[$periodeA][$matricule] ?? null;
         }
     }
 
   // Après le calcul des moyennes et des classements
   foreach ($resultatsA as $resA) {
     $matricule = $resA['eleve']->MATRICULE;
     $moyenneAnnuelle = $resA['moyenneAnnuelle'];
     $rangAnnuel = $resA['rangAnnuel'];
     $appan = $resA['appreciationAnnuelle'];
 
     // Vérifier si la moyenne annuelle n'est pas null
     if ($moyenneAnnuelle !== null) {
         // Données à mettre à jour
         $data = [
             'MAN'  => $moyenneAnnuelle,
             'RANGA' => $rangAnnuel,
             'appan' => $appan,
         ];
 
         // Parcourir les périodes et ajouter les valeurs non nulles
         foreach ($periodsA as $periodeA) {
           $periodeData = $resA['periods'][$periodeA] ?? null;
           if ($periodeData && $periodeData['moyenne'] !== null) {
               $data['MS' . $periodeA] = $periodeData['moyenne'];
               $data['RANG' . $periodeA] = $periodeData['rang'];
               $data['MBILANL' . $periodeA] = $periodeData['bilan_litteraire'];
               $data['MBILANS' . $periodeA] = $periodeData['bilan_scientifique'];
               $data['MoyMatFond' . $periodeA] = $periodeData['bilan_fondamentale'];
               $data['TotalGene' . $periodeA] = $periodeData['totalNotesCoeff'];
               $data['TotalCoef' . $periodeA] = $periodeData['totalCoef'];
               $data['app' . $periodeA] = $periodeData['appreciation'];
           }
         }
 
         // Si TYPEAN == 1 (semestres), forcer les valeurs de la période 3
         if ($typean == 1) {
           $data['MS3'] = -1;
           $data['MBILANL3'] = -1;
           $data['MBILANS3'] = -1;
           $data['MoyMatFond3'] = -1;
           $data['RANG3'] = 0;
           $data['TotalGene3'] = 0;
           $data['TotalCoef3'] = 0;
           $data['app3'] = "";
           // On ne met rien pour 'app3'
         }
 
         // Mettre à jour l'élève dans la base de données
         Eleve::where('MATRICULE', $matricule)->update($data);
     }
   }
 
 
     unset($res);
 
     // Calcul des indicateurs de la classe à partir des élèves de la classe sélectionnée
     $classesUnique = $elevesA->pluck('CODECLAS')->unique();
 
     foreach ($classesUnique as $codeClasse) {
         // Filtrer les élèves appartenant à la classe courante
         $elevesClasse = $elevesA->where('CODECLAS', $codeClasse);
         
         // Récupérer les moyennes par période en filtrant les valeurs null, -1 ou <= 0
         $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function($value) {
             return $value !== null && $value !== -1 && $value > 0;
         })->toArray();
 
         $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function($value) {
             return $value !== null && $value !== -1 && $value > 0;
         })->toArray();
 
         // Pour la période 3, on calcule seulement si TYPEAN n'est pas 1 (donc pour trimestres)
         if ($typean != 1) {
             $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function($value) {
                 return $value !== null && $value !== -1 && $value > 0;
             })->toArray();
         } else {
             $moyennesP3 = []; // pour typean == 1, on force un tableau vide
         }
         
         // Calculer la plus forte et la plus faible moyenne pour chaque période
         $plusGrandeMoyenneP1enr = !empty($moyennesP1) ? max($moyennesP1) : 0;
         $plusFaibleMoyenneP1enr = !empty($moyennesP1) ? min($moyennesP1) : 0;
         
         $plusGrandeMoyenneP2enr = !empty($moyennesP2) ? max($moyennesP2) : 0;
         $plusFaibleMoyenneP2enr = !empty($moyennesP2) ? min($moyennesP2) : 0;
         
         if ($typean != 1) {
             $plusGrandeMoyenneP3enr = !empty($moyennesP3) ? max($moyennesP3) : 0;
             $plusFaibleMoyenneP3enr = !empty($moyennesP3) ? min($moyennesP3) : 0;
         } else {
             // Pour semestres (TYPEAN = 1), on force la période 3 à 0
             $plusGrandeMoyenneP3enr = 0;
             $plusFaibleMoyenneP3enr = 0;
         }
         
         // Calculer la moyenne de la classe pour chaque période
         $moyenneClasseP1enr = count($moyennesP1) > 0 ? array_sum($moyennesP1) / count($moyennesP1) : 0;
         $moyenneClasseP2enr = count($moyennesP2) > 0 ? array_sum($moyennesP2) / count($moyennesP2) : 0;
         if ($typean != 1) {
             $moyenneClasseP3enr = count($moyennesP3) > 0 ? array_sum($moyennesP3) / count($moyennesP3) : 0;
         } else {
             $moyenneClasseP3enr = 0;
         }
         
         // Calculer la moyenne globale de la classe (moyenne arithmétique des moyennes de chaque période)
         $totalMoyennes = 0;
         $nbPeriodes = 0;
         foreach ([$moyenneClasseP1enr, $moyenneClasseP2enr, $moyenneClasseP3enr] as $moy) {
             if ($moy !== null) {
                 $totalMoyennes += $moy;
                 $nbPeriodes++;
             }
         }
         $moyenneClasseGlobaleenr = ($nbPeriodes > 0) ? $totalMoyennes / $nbPeriodes : 0;
         
         // Mettre à jour la classe correspondante dans la table 'classe'
         $classeA = Classes::where('CODECLAS', $codeClasse)->first();
         if ($classeA) {
             $classeA->MFaIBLE1 = $plusFaibleMoyenneP1enr;
             $classeA->MFORTE1  = $plusGrandeMoyenneP1enr;
             $classeA->MFaIBLE2 = $plusFaibleMoyenneP2enr;
             $classeA->MFORTE2  = $plusGrandeMoyenneP2enr;
             $classeA->MFaIBLE3 = $plusFaibleMoyenneP3enr;
             $classeA->MFORTE3  = $plusGrandeMoyenneP3enr;
             $classeA->MCLASSE1 = $moyenneClasseP1enr;
             $classeA->MCLASSE2 = $moyenneClasseP2enr;
             $classeA->MCLASSE3 = $moyenneClasseP3enr;
             $classeA->MCLASSE  = $moyenneClasseGlobaleenr;
             $classeA->save();
         }
     }



    // 
   

    // dd($classe);
          
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
          $resultatEleve = [];



          // Initialisation d'un tableau pour les moyennes annuelles valides
          $moyennesAnnuellesEleves = [];

          foreach ($eleves as $eleve) {
              // On ajoute la moyenne annuelle (MAN) uniquement si elle n'est pas null, différente de -1 et strictement positive (> 0)
              if ($eleve->MAN !== null && $eleve->MAN !== -1 && $eleve->MAN > 0) {
                  $moyennesAnnuellesEleves[] = $eleve->MAN;
              }
          }

          // Trouver la plus grande et la plus faible moyenne dans le tableau filtré
          $plusGrandeMoyenne = !empty($moyennesAnnuellesEleves) ? max($moyennesAnnuellesEleves) : null;
          $plusFaibleMoyenne = !empty($moyennesAnnuellesEleves) ? min($moyennesAnnuellesEleves) : null;

          // Avant de parcourir les élèves, initialiser le tableau pour les moyennes de la période
          $moyennesPeriode = [];

          // Parcourir chaque élève pour calculer les moyennes
          foreach ($eleves as $eleve) {
            $infoClasse = Classes::where('CODECLAS', $eleve->CODECLAS)->first();

            if ($periode === "1"){

              $moyenneSemestrielle =  $eleve->MS1;
              $rang = $eleve->RANG1;
              $billanLitteraire = $eleve->MBILANL1;
              $billanScientifique = $eleve->MBILANS1;
              $billanFondamentale = $eleve->MoyMatFond1;
              $totalGenerale = $eleve->TotalGene1;
              $totalCoefficie = $eleve->TotalCoef1;
              $moyenneClasse = $infoClasse->MCLASSE1;
              $moyenneFaible = $infoClasse->MFaIBLE1;
              $moyenneForte = $infoClasse->MFoRTE1;

            } elseif ($periode === "2") {

              $moyenneSemestrielle =  $eleve->MS2;
              $rang = $eleve->RANG2;
              $billanLitteraire = $eleve->MBILANL2;
              $billanScientifique = $eleve->MBILANS2;
              $billanFondamentale = $eleve->MoyMatFond2;
              $totalGenerale = $eleve->TotalGene2;
              $totalCoefficie = $eleve->TotalCoef2;
              $moyenneClasse = $infoClasse->MCLASSE2;
              $moyenneFaible = $infoClasse->MFaIBLE2;
              $moyenneForte = $infoClasse->MFoRTE2;

            } elseif ($periode === "3") {

              $moyenneSemestrielle =  $eleve->MS3;
              $rang = $eleve->RANG3;
              $billanLitteraire = $eleve->MBILANL3;
              $billanScientifique = $eleve->MBILANS3;
              $billanFondamentale = $eleve->MoyMatFond3;
              $totalGenerale = $eleve->TotalGene3;
              $totalCoefficie = $eleve->TotalCoef3;
              $moyenneClasse = $infoClasse->MCLASSE3;
              $moyenneFaible = $infoClasse->MFaIBLE3;
              $moyenneForte = $infoClasse->MFoRTE3;

            } else {

              return back()->with('erreur', 'veuillez choisir une periode');

            }

               // Ajout : stocker la moyenne de la période si elle est définie, différente de -1 et non nulle (on ignore les 0)
              if ($moyenneSemestrielle !== null && $moyenneSemestrielle !== -1 && $moyenneSemestrielle != 0) {
                $moyennesPeriode[] = $moyenneSemestrielle;
              }

            // CALCUL DU BILAN ANNUELLE DES MATIERES LITTERAIRES
            $bilanLitteraireTotal = 0; // Somme des bilans littéraires valides
            $bilanLitteraireCount = 0; // Compteur des bilans littéraires valides
        
            // Vérifiez et ajoutez chaque bilan littéraire
            $bilanLitteraires = [$eleve->MBILANL1, $eleve->MBILANL2, $eleve->MBILANL3];
            foreach ($bilanLitteraires as $bilan) {
                if ($bilan !== -1 && $bilan !== null) {
                    $bilanLitteraireTotal += $bilan;
                    $bilanLitteraireCount++;
                }
            }
        
            // Calcul de la moyenne (évitez la division par zéro)
            $moyenneBilanLitteraire = $bilanLitteraireCount > 0 ? $bilanLitteraireTotal / $bilanLitteraireCount : null;


            // CALCUL DU BILAN ANNUELLE DES MATIERES SCIENTIFIQUES
            $bilanScientifiqueTotal = 0; // Somme des bilans littéraires valides
            $bilanScientifiqueCount = 0; // Compteur des bilans littéraires valides
        
            // Vérifiez et ajoutez chaque bilan Scientifique
            $bilanScientifiques = [$eleve->MBILANS1, $eleve->MBILANS2, $eleve->MBILANS3];
            foreach ($bilanScientifiques as $bilan) {
                if ($bilan !== -1 && $bilan !== null) {
                    $bilanScientifiqueTotal += $bilan;
                    $bilanScientifiqueCount++;
                }
            }
        
            // Calcul de la moyenne (évitez la division par zéro)
            $moyenneBilanScientifique = $bilanScientifiqueCount > 0 ? $bilanScientifiqueTotal / $bilanScientifiqueCount : null;


            // CALCUL DU BILAN ANNUELLE DES MATIERES FONDAMENTALES 
            $bilanFondamentaleTotal = 0; // Somme des bilans littéraires valides
            $bilanFondamentaleCount = 0; // Compteur des bilans littéraires valides
        
            // Vérifiez et ajoutez chaque bilan Fondamentale
            $bilanFondamentales = [$eleve->MoyMatFond1, $eleve->MoyMatFond2, $eleve->MoyMatFond3];
            foreach ($bilanFondamentales as $bilan) {
                if ($bilan !== -1 && $bilan !== null) {
                    $bilanFondamentaleTotal += $bilan;
                    $bilanFondamentaleCount++;
                }
            }
        
            // Calcul de la moyenne (évitez la division par zéro)
            $moyenneBilanFondamentale = $bilanFondamentaleCount > 0 ? $bilanFondamentaleTotal / $bilanFondamentaleCount : null;


            // CALCUL DES MOYENNES GENERALE EN VERIFIANT LES VALEURS
            $moyenne1erTrimestre_Semestre = 
            ($eleve->TotalGene1 !== -1 && $eleve->TotalGene1 !== null 
             && $eleve->TotalCoef1 !== -1 && $eleve->TotalCoef1 !== null && $eleve->TotalCoef1 > 0)
            ? round(((float)$eleve->TotalGene1 / (float)$eleve->TotalCoef1), 2)
            : null;
        
            $moyenne2emTrimestre_Semestre = 
            ($eleve->TotalGene2 !== -1 && $eleve->TotalGene2 !== null 
             && $eleve->TotalCoef2 !== -1 && $eleve->TotalCoef2 !== null && $eleve->TotalCoef2 > 0)
            ? round(((float)$eleve->TotalGene2 / (float)$eleve->TotalCoef2), 2)
            : null;
        
            $moyenne3emTrimestre_Semestre = 
            ($eleve->TotalGene3 !== -1 && $eleve->TotalGene3 !== null 
             && $eleve->TotalCoef3 !== -1 && $eleve->TotalCoef3 !== null && $eleve->TotalCoef3 > 0)
            ? round(((float)$eleve->TotalGene3 / (float)$eleve->TotalCoef3), 2)
            : null;

            // dd($eleve->TotalCoef2);

            // CALCULE DE LA MOYENNE ANNUELLE DE LA CLASSE
            $moyennesTrimestrielles = [
              $infoClasse->MCLASSE1,
              $infoClasse->MCLASSE2,
              $infoClasse->MCLASSE3
          ];
          
          $somme = 0;
          $compteur = 0;
          
          // Parcourir les moyennes trimestrielles pour les valider
          foreach ($moyennesTrimestrielles as $moyenne) {
              if ($moyenne !== null && $moyenne !== -1) {
                  $somme += $moyenne;
                  $compteur++;
              }
          }
          
          // Calculer la moyenne annuelle si des moyennes valides existent
          $moyenneAnnuelleClasse = $compteur > 0 ? $somme / $compteur : null;




            $resultatEleve = [
              'nom' => $eleve->NOM,
              'prenom' => $eleve->PRENOM,
              'codeweb' => $eleve->CODEWEB,
              'moyenne_semestrielle_1' => $moyenneSemestrielle,
              'rang_1' => $rang,
              'moyenne_bilan_litteraire_1' => $billanLitteraire,
              'moyenne_bilan_scientifique_1' => $billanScientifique,
              'moyenne_bilan_fondamentale_1' => $billanFondamentale,
              'total_notes_1' => $totalGenerale,
              'total_coefficie_1' => $totalCoefficie,
              'redoublant' => $eleve->STATUT,
              'moyenneBilanLitteraire' => $moyenneBilanLitteraire,
              'moyenneBilanScientifique' => $moyenneBilanScientifique,
              'moyenneBilanFondamentale' => $moyenneBilanFondamentale,
              'moyenne1erTrimestre_Semestre' => $moyenne1erTrimestre_Semestre,
              'moyenne2emTrimestre_Semestre' => $moyenne2emTrimestre_Semestre,
              'moyenne3emTrimestre_Semestre' => $moyenne3emTrimestre_Semestre,
              'moyenneAnnuel' => $eleve->MAN,
              'rangAnnuel' => $eleve->RANGA,
              'rang1' => $eleve->RANG1,
              'rang2' => $eleve->RANG2,
              'rang3' => $eleve->RANG3,
              'aptitute_sport' => $eleve->APTE,
              'matricule' => $eleve->MATRICULE,
              'matriculex' => $eleve->MATRICULEX,
              'anneScolaire' => $annescolaire,
              'periode' => $periode,
              'classe' => $infoClasse->CODECLAS,
              'moyenne_classe_1' => $moyenneClasse,
              'moyenne_faible_1' => $moyenneFaible,
              'moyenne_forte_1' => $moyenneForte,
              'plus_grande_moyenne_classe' => $plusGrandeMoyenne,
              'plus_faible_moyenne_classe' => $plusFaibleMoyenne,
              'moyenneAnnueleClasse' => $moyenneAnnuelleClasse,
              'effectif' => $effectifsParClasse[$eleve->CODECLAS] ?? 0,
              'mentionDir' => $this->determineMentionDir($eleve->MS1, $params2),
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
                  'moyenne_interro' => $noteSpeciale,
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
                if ($moyenneInterros !== null  && $moyenneDevoirs !== null) {
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
                
                if ($note->TEST < 21 && $note->TEST != null && $note->TEST != -1 && $note->TEST != 0) {
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

          // Calculer les moyennes par période pour tous les élèves (en filtrant les moyennes <= 0)
          $moyennesP1 = [];
          $moyennesP2 = [];
          $moyennesP3 = [];

          foreach ($eleves as $eleve) {
              // Période 1
              if ($eleve->MS1 !== null && $eleve->MS1 !== -1 && $eleve->MS1 > 0) {
                  $moyennesP1[] = $eleve->MS1;
              }
              // Période 2
              if ($eleve->MS2 !== null && $eleve->MS2 !== -1 && $eleve->MS2 > 0) {
                  $moyennesP2[] = $eleve->MS2;
              }
              // Période 3
              if ($eleve->MS3 !== null && $eleve->MS3 !== -1 && $eleve->MS3 > 0) {
                  $moyennesP3[] = $eleve->MS3;
              }
          }

          // Calculer la plus forte et la plus faible moyenne pour chaque période
          $plusGrandeMoyenneP1 = !empty($moyennesP1) ? max($moyennesP1) : null;
          $plusFaibleMoyenneP1 = !empty($moyennesP1) ? min($moyennesP1) : null;

          $plusGrandeMoyenneP2 = !empty($moyennesP2) ? max($moyennesP2) : null;
          $plusFaibleMoyenneP2 = !empty($moyennesP2) ? min($moyennesP2) : null;

          $plusGrandeMoyenneP3 = !empty($moyennesP3) ? max($moyennesP3) : null;
          $plusFaibleMoyenneP3 = !empty($moyennesP3) ? min($moyennesP3) : null;


          // Calcul de la moyenne de la classe pour chaque période en parcourant tous les élèves
          $totalP1 = 0;
          $countP1 = 0;
          $totalP2 = 0;
          $countP2 = 0;
          $totalP3 = 0;
          $countP3 = 0;

          foreach ($eleves as $eleve) {
              // Période 1 : on prend MS1 uniquement si elle est définie, différente de -1 et supérieure à 0
              if ($eleve->MS1 !== null && $eleve->MS1 !== -1 && $eleve->MS1 > 0) {
                  $totalP1 += $eleve->MS1;
                  $countP1++;
              }
              // Période 2
              if ($eleve->MS2 !== null && $eleve->MS2 !== -1 && $eleve->MS2 > 0) {
                  $totalP2 += $eleve->MS2;
                  $countP2++;
              }
              // Période 3
              if ($eleve->MS3 !== null && $eleve->MS3 !== -1 && $eleve->MS3 > 0) {
                  $totalP3 += $eleve->MS3;
                  $countP3++;
              }
          }

          $moyenneClasseP1 = $countP1 > 0 ? $totalP1 / $countP1 : null;
          $moyenneClasseP2 = $countP2 > 0 ? $totalP2 / $countP2 : null;
          $moyenneClasseP3 = $countP3 > 0 ? $totalP3 / $countP3 : null;

          // Calcul de la moyenne globale de la classe (toutes périodes confondues)
          $totalGlobal = 0;
          $countGlobal = 0;
          if ($moyenneClasseP1 !== null) {
              $totalGlobal += $moyenneClasseP1;
              $countGlobal++;
          }
          if ($moyenneClasseP2 !== null) {
              $totalGlobal += $moyenneClasseP2;
              $countGlobal++;
          }
          if ($moyenneClasseP3 !== null) {
              $totalGlobal += $moyenneClasseP3;
              $countGlobal++;
          }
          $moyenneClasseGlobale = $countGlobal > 0 ? $totalGlobal / $countGlobal : null;

          foreach ($resultats as &$resultat) {
            // attachement des plus forte et faible moyenne de chaque periode aux resultats
              $resultat['plusGrandeMoyenneP1'] = $plusGrandeMoyenneP1;
              $resultat['plusFaibleMoyenneP1'] = $plusFaibleMoyenneP1;
              $resultat['plusGrandeMoyenneP2'] = $plusGrandeMoyenneP2;
              $resultat['plusFaibleMoyenneP2'] = $plusFaibleMoyenneP2;
              $resultat['plusGrandeMoyenneP3'] = $plusGrandeMoyenneP3;
              $resultat['plusFaibleMoyenneP3'] = $plusFaibleMoyenneP3;

            // attachement des moyenne de chaque periode aux resultats
              $resultat['moyenneClasseP1']     = $moyenneClasseP1;
              $resultat['moyenneClasseP2']     = $moyenneClasseP2;
              $resultat['moyenneClasseP3']     = $moyenneClasseP3;
              $resultat['moyenneClasseGlobale'] = $moyenneClasseGlobale;
          }
          unset($resultat); // Bonne pratique pour libérer la référence

          // ENREGISTREMENT DES DONNE DANS LA TABLE CLASSE
          // Récupérer la liste des codes de classe uniques parmi les élèves
          $classesUnique = $eleves->pluck('CODECLAS')->unique();

          foreach ($classesUnique as $codeClasse) {
              // Filtrer les élèves appartenant à la classe courante
              $elevesClasse = $eleves->where('CODECLAS', $codeClasse);
              
              // Récupérer les moyennes par période en filtrant les valeurs null, -1 ou <= 0
              $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function($value) {
                  return $value !== null && $value !== -1 && $value > 0;
              })->toArray();

              $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function($value) {
                  return $value !== null && $value !== -1 && $value > 0;
              })->toArray();

              $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function($value) {
                  return $value !== null && $value !== -1 && $value > 0;
              })->toArray();
              
              // Calculer la plus forte et la plus faible moyenne pour chaque période
              $plusGrandeMoyenneP1enr = !empty($moyennesP1) ? max($moyennesP1) : null;
              $plusFaibleMoyenneP1enr = !empty($moyennesP1) ? min($moyennesP1) : null;
              
              $plusGrandeMoyenneP2enr = !empty($moyennesP2) ? max($moyennesP2) : null;
              $plusFaibleMoyenneP2enr = !empty($moyennesP2) ? min($moyennesP2) : null;
              
              $plusGrandeMoyenneP3enr = !empty($moyennesP3) ? max($moyennesP3) : null;
              $plusFaibleMoyenneP3enr = !empty($moyennesP3) ? min($moyennesP3) : null;
              
              // Calculer la moyenne de la classe pour chaque période
              $moyenneClasseP1enr = count($moyennesP1) > 0 ? array_sum($moyennesP1) / count($moyennesP1) : null;
              $moyenneClasseP2enr = count($moyennesP2) > 0 ? array_sum($moyennesP2) / count($moyennesP2) : null;
              $moyenneClasseP3enr = count($moyennesP3) > 0 ? array_sum($moyennesP3) / count($moyennesP3) : null;
              
              // Calculer la moyenne globale de la classe (si besoin)
              $moyenneClasseGlobale = null;
              $totalMoyennes = 0;
              $nbPeriodes = 0;
              foreach ([$moyenneClasseP1enr, $moyenneClasseP2enr, $moyenneClasseP3enr] as $moy) {
                  if ($moy !== null) {
                      $totalMoyennes += $moy;
                      $nbPeriodes++;
                  }
              }
              if ($nbPeriodes > 0) {
                  $moyenneClasseGlobaleenr = $totalMoyennes / $nbPeriodes;
              }
              
              // Mettre à jour la classe correspondante dans la table 'classe'
              $classe = Classes::where('CODECLAS', $codeClasse)->first();
              if ($classe) {
                  $classe->MFaIBLE1 = $plusFaibleMoyenneP1enr;
                  $classe->MFORTE1  = $plusGrandeMoyenneP1enr;
                  $classe->MFaIBLE2 = $plusFaibleMoyenneP2enr;
                  $classe->MFORTE2  = $plusGrandeMoyenneP2enr;
                  $classe->MFaIBLE3 = $plusFaibleMoyenneP3enr;
                  $classe->MFORTE3  = $plusGrandeMoyenneP3enr;
                  $classe->MCLASSE1 = $moyenneClasseP1enr;
                  $classe->MCLASSE2 = $moyenneClasseP2enr;
                  $classe->MCLASSE3 = $moyenneClasseP3enr;
                  $classe->MCLASSE  = $moyenneClasseGlobaleenr;
                  $classe->save();
              }
          }

                    // RECUPERER TOUTE LES MOYENNE ANNUELLE DES ELEVES DE LA CLASSE ET TRIER POUR TROUVER LA PLUS FORTE ET LA PLUS FAIBLE
                    // Initialisation d'un tableau pour stocker les moyennes annuelles des élèves de la classe
          //           $moyennesAnnuellesEleves = [];

          //           // Parcourir les élèves pour récupérer leurs moyennes annuelles
          //           foreach ($eleves as $eleve) {
          //             $infoclasses = Classes::where('CODECLAS', $eleve->CODECLAS)->first();

          //               if ($eleve->CODECLAS === $infoclasses->CODECLAS) {
          //                   // Vérifier que la moyenne annuelle (MAN) est valide (différente de null et -1)
          //                   if ($eleve->MAN !== null && $eleve->MAN !== -1) {
          //                       $moyennesAnnuellesEleves[] = $eleve->MAN;
          //                   }
          //               }
          //           }

          //           // Trouver la plus grande et la plus faible moyenne dans le tableau
          //           $plusGrandeMoyenne = !empty($moyennesAnnuellesEleves) ? max($moyennesAnnuellesEleves) : null;
          //           $plusFaibleMoyenne = !empty($moyennesAnnuellesEleves) ? min($moyennesAnnuellesEleves) : null;

          //           // Ajouter les résultats au tableau final ou utiliser les valeurs
          //           $resultatEleve['plus_grande_moyenne_classe'] = $plusGrandeMoyenne;
          //           $resultatEleve['plus_faible_moyenne_classe'] = $plusFaibleMoyenne;

          // dd($resultats);

          // dd($moyennesParClasseEtMatiere);

          // Calculer le rang pour chaque matière et chaque classe
          foreach ($moyennesParClasseEtMatiere as $classe => $matieres) {
            foreach ($matieres as $matiere => $moyennes) {
                  // Filtrer les moyennes pour exclure celles qui valent 0
                  // $moyennes = array_filter($moyennes, function($item) {
                  //   return $item['moyenne'] > 0;
                  // });
                  // // Si après filtrage il n'y a aucune moyenne, on passe à la suivante
                  // if (empty($moyennes)) {
                  //   continue;
                  // }

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
          
          return view('pages.notes.printbulletindenotes', compact('request', 'resultats', 'eleves', 'option', 'entete', 'typean', 'params2', 'logo', 'logoBase64', 'mimeType'));
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
            } elseif ($moyenne <= $params2->Borne5) {
                return $params2->Mention5p;
            } elseif ($moyenne <= $params2->Borne6) {
                return $params2->Mention6p;
            } elseif ($moyenne <= $params2->Borne7) {
                return $params2->Mention7p;
            } else {
                return $params2->Mention8p;
            }
        }
        private function determineMentionDir($moyenne, $params2)
        {
            if ($moyenne < $params2->Borne1) {
                return $params2->Mention1d;
            } elseif ($moyenne <= $params2->Borne2) {
                return $params2->Mention2d;
            } elseif ($moyenne <= $params2->Borne3) {
                return $params2->Mention3d;
            } elseif ($moyenne <= $params2->Borne4) {
                return $params2->Mention4d;
            } elseif ($moyenne <= $params2->Borne5) {
                return $params2->Mention5d;
            } elseif ($moyenne <= $params2->Borne6) {
                return $params2->Mention6d;
            } elseif ($moyenne <= $params2->Borne7) {
                return $params2->Mention7d;
            } else {
                return $params2->Mention8d;
            }
        }

        // dd($resultats);


        public function extrairenote() {

          $classes = Classes::get();
          $matieres = Matieres::get();

          return view('pages.notes.extrairenote', compact('classes', 'matieres'));
        }
    

        public function extractnote(Request $request) {

            $classes = Classes::get();
            $matieres = Matieres::get();  


            $periode = $request->input('periode');
            $classe = $request->input('classe');
            $matiere = $request->input('matiere');

            $notes = Notes::where('CODECLAS', $classe)
            ->where('CODEMAT', $matiere)
            ->where('SEMESTRE', $periode)
            ->get();

            // dd($notes);

            return view('pages.notes.extractnote', compact('classes', 'matieres', 'notes'));


        }


            // Méthode pour exporter en Excel
            public function exportExcel(Request $request)
            {
                $periode = $request->input('periode');
                $classe  = $request->input('classe');
                $matiere = $request->input('matiere');
            
                $notes = Notes::where('CODECLAS', $classe)
                              ->where('CODEMAT', $matiere)
                              ->where('SEMESTRE', $periode)
                              ->get();
            
                // Récupérer les options d'export (1 = coché, 0 = décoché)
                $exportMoy = $request->input('exportMoy', 1);
                $exportDev1 = $request->input('exportDev1', 1);
                $exportDev2 = $request->input('exportDev2', 1);
            
                return Excel::download(new NotesExport($notes, $exportMoy, $exportDev1, $exportDev2), 'notes.xlsx');
            }
        
      }           
      