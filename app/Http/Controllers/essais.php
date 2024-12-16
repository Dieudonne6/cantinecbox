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

    public function printbulletindenotes(Request $request)
    {
        $option = Session::get('option');
        
        // Extraire les valeurs des entrées
        $inputs = $this->extraireInputs($request);
        
        // Récupérer les paramètres généraux
        $params2 = Params2::first();
        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours . '-' . $annesuivante;
        $rtfContent = $params2->EnteteBull;
        $entete = $this->extraireTexteDuRtf($rtfContent);
        
        // Filtrer les classes sélectionnées
        $classeSelectionne = $this->filtrerClasses($inputs['selected_classes']);
        
        // Mettre à jour les classements
        $this->mettreAJourClassements($classeSelectionne);
        
        // Mettre à jour les moyennes des élèves
        $this->mettreAJourMoyennesEleves();
        
        // Traiter les bonifications (le cas échéant)
        $this->traiterBonifications($inputs['bonification'], $inputs['bonificationType']);
        
        // Calculer les moyennes du semestre
        $this->calculerMoyennesSemestres($inputs, $option, $anneencours, $annescolaire);
        
        // Calculs supplémentaires pour chaque type de matière
        $this->calculerMoyennesMatieres($inputs, $option);
    
    
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
          $resultatEleve = [];



          // Initialisation d'un tableau pour les moyennes annuelles des élèves de la classe
          $moyennesAnnuellesEleves = [];

          // Parcourir les élèves de la classe pour récupérer leurs moyennes annuelles
          foreach ($eleves as $eleve) {
              // Vérifier que la moyenne annuelle (MAN) est valide (différente de null et -1)
              if ($eleve->MAN !== null && $eleve->MAN !== -1) {
                  $moyennesAnnuellesEleves[] = $eleve->MAN;
              }
          }
          // Trouver la plus grande et la plus faible moyenne dans le tableau
          $plusGrandeMoyenne = !empty($moyennesAnnuellesEleves) ? max($moyennesAnnuellesEleves) : null;
          $plusFaibleMoyenne = !empty($moyennesAnnuellesEleves) ? min($moyennesAnnuellesEleves) : null;

          // Parcourir chaque élève pour calculer les moyennes
          foreach ($eleves as $eleve) {
            $infoClasse = Classes::where('CODECLAS', $eleve->CODECLAS)->first();

            if ($periode === "1"){

              $moyenneSemestrielle =  $eleve->MS1;
              $rang = $eleve->RANG1;
              $billanLitteraire = $eleve->MBILANL1;
              $billanScientifique = $eleve->MBILANS1;
              $billanFondamentale = $eleve->MoyMatFond1;
              $totalGenerale = $eleve->TotalGen1;
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
              $totalGenerale = $eleve->TotalGen2;
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
              $totalGenerale = $eleve->TotalGen3;
              $totalCoefficie = $eleve->TotalCoef3;
              $moyenneClasse = $infoClasse->MCLASSE3;
              $moyenneFaible = $infoClasse->MFaIBLE3;
              $moyenneForte = $infoClasse->MFoRTE3;

            } else {

              return back()->with('erreur', 'veuillez choisir une periode');

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
            $moyenne1erTrimestre_Semestre = ($eleve->TotalGen1 !== -1 && $eleve->TotalGen1 !== null && $eleve->TotalCoef1 !== -1 && $eleve->TotalCoef1 !== null && $eleve->TotalCoef1 > 0) 
            ? $eleve->TotalGen1 / $eleve->TotalCoef1 
            : null;

            $moyenne2emTrimestre_Semestre = ($eleve->TotalGen2 !== -1 && $eleve->TotalGen2 !== null && $eleve->TotalCoef2 !== -1 && $eleve->TotalCoef2 !== null && $eleve->TotalCoef2 > 0) 
                ? $eleve->TotalGen2 / $eleve->TotalCoef2 
                : null;

            $moyenne3emTrimestre_Semestre = ($eleve->TotalGen3 !== -1 && $eleve->TotalGen3 !== null && $eleve->TotalCoef3 !== -1 && $eleve->TotalCoef3 !== null && $eleve->TotalCoef3 > 0) 
                ? $eleve->TotalGen3 / $eleve->TotalCoef3 
                : null;


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

          // dd($resultatEleve);

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

  private function extraireInputs($request)
{
    return [
        'paramselection' => $request->input('paramselection'),
        'bonificationType' => $request->input('bonificationType'),
        'bonification' => $request->input('bonification'),
        'msgEnBasBulletin' => $request->input('msgEnBasBulletin'),
        'periode' => $request->input('periode'),
        'conduite' => $request->input('conduite'),
        'eps' => $request->input('eps'),
        'nbabsence' => $request->input('nbabsence'),
        'apartirde' => $request->input('apartirde'),
        'selected_classes' => $request->input('selected_classes', []),
    ];
}

private function filtrerClasses($selectedClasses)
{
    return array_filter($selectedClasses, function ($value) {
        return $value !== 'all';
    });
}

private function mettreAJourClassements($classeSelectionne)
{
    $clases = DB::table('eleve')->select('CODECLAS')->distinct()->get();
    
    foreach ($clases as $classe) {
        $eleves = DB::table('eleve')
            ->where('CODECLAS', $classe->CODECLAS)
            ->whereNotNull('MAN')
            ->orderBy('MAN', 'desc')
            ->get();
        
        $rank = 1;
        $previousMan = null;
        
        foreach ($eleves as $eleve) {
            if ($previousMan !== null && $eleve->MAN !== $previousMan) {
                $rank++;
            }
            
            DB::table('eleve')
                ->where('MATRICULE', $eleve->MATRICULE)
                ->update(['RANGA' => $rank]);
            
            $previousMan = $eleve->MAN;
        }
    }
}

private function mettreAJourMoyennesEleves()
{
    $eles = DB::table('eleve')->get();
    
    foreach ($eles as $eleve) {
        $notes = [];
        for ($i = 1; $i <= 12; $i++) {
            $ms = $eleve->{'MS' . $i};
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
}

private function traiterBonifications($bonifications, $bonificationType)
{
    foreach ($bonifications as $bonification) {
        $start = $bonification['start'];
        $end = $bonification['end'];
        $note = $bonification['note'];
        // Traiter la logique des bonifications si nécessaire
    }
}

private function calculerMoyennesSemestres($inputs, $option, $anneencours, $annescolaire)
{
    $semestres = DB::table('notes')->distinct()->pluck('SEMESTRE');
    $typesMatieres = DB::table('matieres')->distinct()->pluck('TYPEMAT');
    $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
    
    $eleves = Eleve::whereIn('CODECLAS', $inputs['selected_classes'])
        ->with(['notes' => function ($query) use ($inputs) {
            $query->where('SEMESTRE', $inputs['periode']);
        }])->get();
    
    foreach ($semestres as $semestre) {
        $this->calculerMoyennesSemestreParClasse($semestre, $option, $inputs['bonificationType'], $inputs['bonification']);
    }
}

private function calculerMoyennesSemestreParClasse($semestre, $option, $bonificationType, $bonifications)
{
    $classes = DB::table('eleve')->distinct()->pluck('CODECLAS');
    
    foreach ($classes as $classe) {
        $eleves = DB::table('eleve')->where('CODECLAS', $classe)->get();
        
        foreach ($eleves as $eleve) {
            $notes = DB::table('notes')
                ->where('MATRICULE', $eleve->MATRICULE)
                ->where('SEMESTRE', $semestre)
                ->get();
            
            $totalCoef = 0;
            $totalMSCoef = 0;
            
            foreach ($notes as $note) {
                $this->traiterNote($note, $option, $bonificationType, $bonifications, $totalMSCoef, $totalCoef);
            }
            
            if ($totalCoef > 0) {
                $moyenne = $totalMSCoef / $totalCoef;
                $this->mettreAJourMoyenneSemestreEleve($eleve->MATRICULE, $semestre, $moyenne, $totalMSCoef, $totalCoef);
            }
        }
        
        $this->classerClasseParSemestre($classe, $semestre);
    }
}

private function traiterNote($note, $option, $bonificationType, $bonifications, &$totalMSCoef, &$totalCoef)
{
    // Traiter les notes selon la logique de bonification
    // Exemple de logique pour calculer totalMSCoef et totalCoef en fonction des conditions sur la note
}

private function mettreAJourMoyenneSemestreEleve($matricule, $semestre, $moyenne, $totalMSCoef, $totalCoef)
{
    $colonne = 'MS' . $semestre;
    if (Schema::hasColumn('eleve', $colonne)) {
        DB::table('eleve')
            ->where('MATRICULE', $matricule)
            ->update([$colonne => $moyenne]);
    }
    
    $columgene = 'TotalGene' . $semestre;
    $columcoef = 'TotalCoef' . $semestre;
    
    if (Schema::hasColumn('eleve', $columgene)) {
        DB::table('eleve')
            ->where('MATRICULE', $matricule)
            ->update([$columgene => $totalMSCoef]);
    }
    if (Schema::hasColumn('eleve', $columcoef)) {
        DB::table('eleve')
            ->where('MATRICULE', $matricule)
            ->update([$columcoef => $totalCoef]);
    }
}

private function classerClasseParSemestre($classe, $semestre)
{
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
            $identicalRank++;
        } else {
            $rang += $identicalRank;
            $identicalRank = 1;
        }
        
        $rangColumn = 'RANG' . $semestre;
        if (Schema::hasColumn('eleve', $rangColumn)) {
            DB::table('eleve')
                ->where('MATRICULE', $eleveClasse->MATRICULE)
                ->update([$rangColumn => $rang]);
        }
        
        $lastMoyenne = $moyenne;
    }
}

private function calculerMoyennesMatieres($inputs, $option)
{
    $typesMatieres = DB::table('matieres')->distinct()->pluck('TYPEMAT');
    $semestres = DB::table('notes')->distinct()->pluck('SEMESTRE');
    
    foreach ($typesMatieres as $typeMat) {
        foreach ($semestres as $semestre) {
            $this->calculerMoyenneMatieresParSemestre($typeMat, $semestre, $inputs);
        }
    }
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

}