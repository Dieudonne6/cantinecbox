<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\Delevea;
use App\Models\Params2;
use App\Models\Matieres;
use App\Models\Classes;
use App\Models\Notes;
use App\Models\Classe;

use App\Models\Typeenseigne;

use Illuminate\Support\Facades\DB;


class EditionController extends Controller
{
    public function editions(){
        
        // LISTE DES ELEVES DONT ARRIERE EST != 0
        $listeElevesArr = Eleve::where('ARRIERE', '!=', 0)->get();
        // Initialiser un tableau pour stocker les résultats
        $resultats = [];
        
        // Parcourir chaque élève
        foreach ($listeElevesArr as $eleve) {
            
            // Calculer la somme des a_payer où autreref est 2 et le matricule correspond à celui de l'élève
            $somme = Scolarite::where('AUTREF', 2)
            ->where('MATRICULE', $eleve->MATRICULE)
            ->sum('MONTANT');
            
            // dd($somme);
            
            $RESTE = $eleve->ARRIERE - $somme;
            // Ajouter le résultat au tableau
            $resultats[$eleve->MATRICULE] = [
                'NOM' => $eleve->NOM,
                'PRENOM' => $eleve->PRENOM,
                'CLASSE' => $eleve->CODECLAS,
                'ARRIERE' => $eleve->ARRIERE,
                'PAYE' => $somme,
                'RESTE' => $RESTE,
            ];
        }    
        
        $totalDues = 0;
        $totalPayes = 0;
        $totalRestes = 0;
        
        foreach ($resultats as $resultat) {
            $totalDues += $resultat['ARRIERE'];
            $totalPayes += $resultat['PAYE'];
            $totalRestes += $resultat['RESTE'];
        }
        // dd($resultats);
        $eleves = Eleve::where('EcheancierPerso', 1)
        ->orderBy('CODECLAS')
        ->get();
        
        return view('pages.inscriptions.editions', compact('eleves','resultats','totalDues','totalPayes','totalRestes'));
    } 
    
    public function arriereconstate(Request $request) {
        // Récupérer les dates du formulaire
        $datedebut = $request->input('datedebut');
        $datefin = $request->input('datefin');
        
        // Vérifier que les dates ont bien été soumises
        if ($datedebut && $datefin) {
            // Récupérer les lignes de scolarite avec jointure sur la table eleve
            $resultats = Scolarite::select(
                'scolarit.DATEOP',
                'scolarit.MONTANT',
                'scolarit.MATRICULE',
                'eleve.NOM',
                'eleve.PRENOM',
                'eleve.CODECLAS',
                'scolarit.SIGNATURE'
                )
                ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE')
                ->where('scolarit.AUTREF', 2)
                ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin])
                ->orderBy('scolarit.DATEOP')
                ->get();
                
                // Pour chaque résultat, récupérer la classe précédente depuis la table 'elevea' de la base de données 'scoracine'
                $resultats->map(function($resultat) {
                    // Récupérer la classe précédente (dernier enregistrement dans la table 'elevea' pour le matricule)
                    $classePrecedente = DB::connection('mysql2')
                    ->table('delevea')
                    ->where('MATRICULE', $resultat->MATRICULE)
                    ->orderBy('ANSCOL', 'desc') // Trier pour obtenir la dernière entrée
                    ->value('CODECLAS'); // Récupérer le code de la classe
                    
                    // Ajouter la classe précédente au résultat
                    $resultat->classe_precedente = $classePrecedente;
                    
                    return $resultat;
                });
                
                // Regrouper par DATEOP
                $groupedResultats = $resultats->groupBy('DATEOP');
                
                // Retourner la vue avec les résultats groupés
                return view('pages.inscriptions.arriereconstate', compact('groupedResultats', 'datedebut', 'datefin'));
            } else {
                return back()->with('error', 'Veuillez fournir une date de début et une date de fin.');
            }
        }
        public function journaldetailleaveccomposante(Request $request) {
            // Récupérer les paramètres de filtrage depuis la requête
            $datedebut = $request->query('debut');
            $datefin = $request->query('fin');
            $typeenseign = $request->query('typeenseign');
            // Requête pour récupérer les données
            $recouvrements = DB::table('scolarit')
            ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE') // Joindre la table des élèves
            ->join('typeenseigne', 'eleve.TYPEENSEIG', '=', 'typeenseigne.idenseign') // Joindre la table des types d'enseignement
            ->select('scolarit.DATEOP', 'scolarit.AUTREF', 'scolarit.EDITE', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type', DB::raw('SUM(scolarit.MONTANT) as total'))
            ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin]) // Filtrer par dates
            ->where('eleve.TYPEENSEIG', '=', $typeenseign) // Filtrer par type d'enseignement
            ->where('scolarit.VALIDE', '=', 1) // Filtrer les enregistrements où validate est égal à 1
            ->groupBy('scolarit.DATEOP', 'scolarit.AUTREF', 'scolarit.EDITE', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type') // Regrouper par date et autres champs
            ->orderBy('scolarit.DATEOP', 'asc') // Trier par date
            ->get();
            $enseign = Typeenseigne::where('idenseign', $typeenseign)->first();
            
            $libelle = Params2::first();
            // Retourner la vue avec les données
            return view('pages.inscriptions.journaldetailleaveccomposante', compact('recouvrements', 'libelle', 'enseign'));
        }
        
        public function journaldetaillesanscomposante(Request $request) {
            // Récupérer les paramètres de filtrage depuis la requête
            $datedebut = $request->query('debut');
            $datefin = $request->query('fin');
            $typeenseign = $request->query('typeenseign');
            // Requête pour récupérer les données
            $recouvrements = DB::table('scolarit')
            ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE') // Joindre la table des élèves
            ->join('typeenseigne', 'eleve.TYPEENSEIG', '=', 'typeenseigne.idenseign') // Joindre la table des types d'enseignement
            ->select('scolarit.DATEOP', 'scolarit.SIGNATURE', 'scolarit.NUMRECU', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type', DB::raw('SUM(scolarit.MONTANT) as total'))
            ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin]) // Filtrer par dates
            ->where('eleve.TYPEENSEIG', '=', $typeenseign) // Filtrer par type d'enseignement
            ->where('scolarit.VALIDE', '=', 1) // Filtrer les enregistrements où validate est égal à 1
            ->groupBy('scolarit.DATEOP', 'scolarit.SIGNATURE', 'scolarit.NUMRECU', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type') // Regrouper par date et autres champs
            ->orderBy('scolarit.DATEOP', 'asc') // Trier par date
            ->get();
            $enseign = Typeenseigne::where('idenseign', $typeenseign)->first();
            $libelle = Params2::first();
            // Retourner la vue avec les données
            return view('pages.inscriptions.journaldetaillesanscomposante', compact('recouvrements', 'libelle','enseign'));
        }
        
        public function tabledesmatieres() {
            $matiere = Matieres::all();
            $lastMatiere = Matieres::orderBy('CODEMAT', 'desc')->first();
            $nextCodeMat = $lastMatiere ? $lastMatiere->CODEMAT + 1 : 1; // Si aucune matière, commencer à 1
            return view('pages.notes.tabledesmatieres', compact('matiere',  'nextCodeMat',  'lastMatiere'));
        }
        public function filtrereleveparmatiere(Request $request) {
            $classe = $request->classe;
            $matiere = $request->matiere;
            // dd($classe);
            $notes = Notes::join('eleve', 'notes.MATRICULE', '=', 'eleve.MATRICULE')
            ->select('notes.MATRICULE', 'eleve.nom', 'eleve.prenom', 'notes.INT1', 'notes.INT2', 'notes.INT3', 'notes.INT4', 'notes.MI', 'notes.DEV1', 'notes.DEV2', 'notes.DEV3')
            ->when($classe, function ($q) use ($classe) {
                return $q->where('notes.CODECLAS', $classe);
            })
            ->when($matiere, function ($q) use ($matiere) {
                return $q->where('notes.CODEMAT', $matiere);
            })
            ->get();
            $matiere = Matieres::where('CODEMAT', $matiere)->first();
            $matiere = $matiere['LIBELMAT'];
            return view('pages.notes.filtrereleveparmatiere', compact('notes','classe','matiere'));
            
        }
        public function storetabledesmatieres(Request $request) {
            $matiere = new Matieres();
            $matiere->LIBELMAT = $request->libelle;
            $matiere->NOMCOURT = $request->nomcourt;
            $matiere->COULEUR = $request->couleur;
            $matiere->CODEMAT_LIGNE = $request->matligne;
            $lastMatiere = Matieres::orderBy('CODEMAT', 'desc')->first();
            $matiere->CODEMAT = $lastMatiere ? $lastMatiere->CODEMAT + 1 : 1;
            if ($request->input('typematiere') == 1) {
                $matiere->TYPEMAT = 1;
            }
            if ($request->input('typematiere') == 2) {
                $matiere->TYPEMAT = 2;
            } else {
                $matiere->TYPEMAT = 3;
            }
            
            // Vérification de l'écriture
            $matiere->COULEURECRIT = $request->input('ecrit') ? 0 : 16777215;
            
            $matiere->save();
            return redirect()->route('tabledesmatieres')->with('status', 'Matière enregistrée avec succès');
        }
        public function updatetabledesmatieres(Request $request) {
            // Validation des données
            $request->validate([
                'libelleModif' => 'required|string|max:255',
                'nomcourtModif' => 'required|string|max:255',
                'couleurModif' => 'required|string|max:7',
                'ecritModif' => 'boolean',
                'codemat' => 'required|integer',
            ]);
            $code = $request->input('codemat');
            $matiere = Matieres::where('CODEMAT', $code)->first();
            
            // Vérifiez si la matière existe
            if (!$matiere) {
                return redirect()->route('tabledesmatieres')->with('status', 'Matière non trouvée');
            }
            
            // Mise à jour des champs
            $matiere->LIBELMAT = $request->input('libelleModif');
            $matiere->NOMCOURT = $request->input('nomcourtModif');
            $matiere->COULEUR = $request->input('couleurModif') ?? '#FFFFFF';
            $matiere->CODEMAT_LIGNE = $request->input('matligneModif') ?? 0;
            // Mise à jour du type de matière
            $matiere->TYPEMAT = $request->input('typematiereModif') == 1 ? 1 : ($request->input('typematiereModif') == 2 ? 2 : 3); // Changer ici
            
            // Vérification de l'écriture
            $matiere->COULEURECRIT = $request->input('ecritModif') ? 0 : 16777215;
            
            // Sauvegarde des modifications
            $matiere->save();
            
            return redirect()->route('tabledesmatieres')->with('status', 'Matière mise à jour avec succès');
        }
        
        public function elevessansnote($classCode) {
            // Récupère les élèves de la classe spécifique
            $students = Eleve::where('CODECLAS', $classCode)->get();
            dd($students);
            // Formate la réponse JSON
            return response()->json([
                'students' => $students->map(function ($student) {
                    return [
                        'matricule' => $student->MATRICULE,
                        'sexe' => $student->SEXE === 1 ? 'M' : 'F',
                        'nom' => $student->NOM,
                        'prenom' => $student->PRENOM,
                        // 'int1' => $student->int1,
                        // 'int2' => $student->int2,
                        // 'int3' => $student->int3,
                        // 'int4' => $student->int4,
                        // 'mi' => $student->mi,
                        // 'dev1' => $student->dev1,
                        // 'dev2' => $student->dev2,
                        // 'dev3' => $student->dev3,
                        // 'compo' => $student->compo,
                    ];
                })
            ]);
        }
        public function relevesparmatiere(){
            $classe = Classes::all();
            $matieres = Matieres::all();
            return view('pages.notes.relevesparmatiere', compact('classe', 'matieres'));
        }
        public function tableaudenotes(){
            $classe = Classes::all();
            $matieres = Matieres::all();
            return view('pages.notes.tableaudenotes');
        }
        public function filtertableaunotes(Request $request) {
            $intervals = [
                ['min' => $request->input('interval1'), 'max' => $request->input('interval2')],
                ['min' => $request->input('interval3'), 'max' => $request->input('interval4')],
                ['min' => $request->input('interval5'), 'max' => $request->input('interval6')],
                ['min' => $request->input('interval7'), 'max' => $request->input('interval8')],
                ['min' => $request->input('interval9'), 'max' => $request->input('interval10')],
            ];
            $classe = Classe::all();
            // Récupérer la classe, la période et le type d'évaluation sélectionnés
            $selectedClass = $request->input('classe','CE1A');
            $selectedPeriod = $request->input('periode',1);
            $selectedEvaluation = $request->input('note','DEV1'); // par exemple "Dev1"
            
            // Récupérer les matières de la classe sélectionnée
            $matieres = DB::table('clasmat')
            ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
            ->where('clasmat.CODECLAS', $selectedClass)
            ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
            ->get();
            
            $eleves = DB::table('eleve')
            ->where('CODECLAS', $selectedClass)
            ->select('MATRICULE', 'NOM', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
            ->get();
            
            
            $notes = DB::table('notes')
            ->where('CODECLAS', $selectedClass)
            ->where('SEMESTRE', $selectedPeriod)
            ->select('MATRICULE', 'CODEMAT', $selectedEvaluation)
            ->get()
            ->keyBy(function ($note) {
                return $note->MATRICULE . '-' . $note->CODEMAT;
            });
            $moyennes = [];
            foreach ($matieres as $matiere) {
                $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiere, $selectedEvaluation) {
                    return $note->CODEMAT === $matiere->CODEMAT && isset($note->$selectedEvaluation);
                });
                $matiereScores = array_column($matiereNotes, $selectedEvaluation);
                $moyennes[$matiere->CODEMAT] = [
                    'min' => count($matiereScores) > 0 ? min($matiereScores) : 0,
                    'max' => count($matiereScores) > 0 ? max($matiereScores) : 0
                ];
            }
            $moyenneCounts = [];
            foreach ($matieres as $matiere) {
                $matiereCode = $matiere->CODEMAT;
                $moyenneCounts[$matiereCode] = [];
                
                // Filtrer les notes pour cette matière avec le selectedEvaluation
                $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiereCode, $selectedEvaluation) {
                    return $note->CODEMAT === $matiereCode && isset($note->$selectedEvaluation);
                });
                
                // Extraire les notes pour cette matière spécifique
                $matiereScores = array_column($matiereNotes, $selectedEvaluation);
                
                // Compter les notes dans chaque intervalle
                foreach ($intervals as $interval) {
                    $count = count(array_filter($matiereScores, function ($score) use ($interval) {
                        return $score >= $interval['min'] && $score < $interval['max'];
                    }));
                    $moyenneCounts[$matiereCode][] = $count;
                }
            }
            
            return view('pages.notes.filtertableaunotes', compact('classe','matieres','eleves','notes','selectedEvaluation','moyennes','moyenneCounts', 'intervals'));
            
        }
        public function filtertablenotes(Request $request) {
            $intervals = [
                ['min' => $request->input('interval1'), 'max' => $request->input('interval2')],
                ['min' => $request->input('interval3'), 'max' => $request->input('interval4')],
                ['min' => $request->input('interval5'), 'max' => $request->input('interval6')],
                ['min' => $request->input('interval7'), 'max' => $request->input('interval8')],
                ['min' => $request->input('interval9'), 'max' => $request->input('interval10')],
            ];
            $classe = Classe::all();
            // Récupérer la classe, la période et le type d'évaluation sélectionnés
            $selectedClass = $request->input('classe');
            $selectedPeriod = $request->input('periode');
            $selectedEvaluation = $request->input('note'); // par exemple "Dev1"
            
            // Récupérer les matières de la classe sélectionnée
            $matieres = DB::table('clasmat')
            ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
            ->where('clasmat.CODECLAS', $selectedClass)
            ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
            ->get();
            
            $eleves = DB::table('eleve')
            ->where('CODECLAS', $selectedClass)
            ->select('MATRICULE', 'NOM', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
            ->get();
            
            
            $notes = DB::table('notes')
            ->where('CODECLAS', $selectedClass)
            ->where('SEMESTRE', $selectedPeriod)
            ->select('MATRICULE', 'CODEMAT', $selectedEvaluation)
            ->get()
            ->keyBy(function ($note) {
                return $note->MATRICULE . '-' . $note->CODEMAT;
            });
            $moyennes = [];
            foreach ($matieres as $matiere) {
                $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiere, $selectedEvaluation) {
                    return $note->CODEMAT === $matiere->CODEMAT && isset($note->$selectedEvaluation);
                });
                $matiereScores = array_column($matiereNotes, $selectedEvaluation);
                $moyennes[$matiere->CODEMAT] = [
                    'min' => count($matiereScores) > 0 ? min($matiereScores) : 0,
                    'max' => count($matiereScores) > 0 ? max($matiereScores) : 0
                ];
            }
            $moyenneCounts = [];
            foreach ($matieres as $matiere) {
                $matiereCode = $matiere->CODEMAT;
                $moyenneCounts[$matiereCode] = [];
                
                // Filtrer les notes pour cette matière avec le selectedEvaluation
                $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiereCode, $selectedEvaluation) {
                    return $note->CODEMAT === $matiereCode && isset($note->$selectedEvaluation);
                });
                
                // Extraire les notes pour cette matière spécifique
                $matiereScores = array_column($matiereNotes, $selectedEvaluation);
                
                // Compter les notes dans chaque intervalle
                foreach ($intervals as $interval) {
                    $count = count(array_filter($matiereScores, function ($score) use ($interval) {
                        return $score >= $interval['min'] && $score < $interval['max'];
                    }));
                    $moyenneCounts[$matiereCode][] = $count;
                }
            }
            
            
            return view('pages.notes.filtertablenotes', compact('classe','matieres','eleves','notes','selectedEvaluation','moyennes','moyenneCounts', 'intervals'));
            
        }
    }
    
    