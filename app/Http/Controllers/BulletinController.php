<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
use App\Models\Imgbulletin;
use App\Models\DecisionConfiguration;
use App\Models\PeriodeSave;

use PhpOffice\PhpSpreadsheet\IOFactory;
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
use Illuminate\Support\Str;

use App\Imports\ElevesImport;
use App\Exports\NotesClasseMultiExport;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

use PDF;



// use Maatwebsite\Excel\Facades\Excel;



class BulletinController extends Controller
{

    public function getConfig($promotion)
    {
        $cfg = DecisionConfiguration::where('promotion', $promotion)->first();
        if (! $cfg) {
            return response()->json(null, 204);   // pas de contenu
        }

        // Recomposer la structure “intervals” exactement comme dans ton formulaire
        $intervals = ['non' => [], 'doublant' => []];
        for ($i = 1; $i <= 5; $i++) {
            $intervals['non'][$i] = [
                'min'     => $cfg->{"NouveauBorneI{$i}A"},
                'max'     => $cfg->{"NouveauBorneI{$i}B"},
                'libelle' => $cfg->{"NouveauLibelleI{$i}"},
            ];
            $intervals['doublant'][$i] = [
                'min'     => $cfg->{"AncienBorneI{$i}A"},
                'max'     => $cfg->{"AncienBorneI{$i}B"},
                'libelle' => $cfg->{"AncienLibelleI{$i}"},
            ];
        }

        return response()->json($intervals);
    }

    public function bulletindenotes()
    {
        $classes = Classes::withCount(['eleves' => function ($query) {
            $query->where('CODECLAS', '!=', '');
        }])->get();
        $classesg = Groupeclasse::select('LibelleGroupe')->distinct()->get();
        $promotions = Promo::all();
        $matieres = Matiere::all();
        $eleves = Eleve::all();
        $params2 = Params2::first();
        $typean = $params2->TYPEAN;
        $current = PeriodeSave::where('key', 'active')->value('periode');

        // 1) On met en session tout ce qu'il faut reprendre après configurerDecisions()
        session()->put('bulletinInit', compact(
            'classes',
            'classesg',
            'promotions',
            'matieres',
            'eleves',
            'current',
            'typean'
        ));

        return view('pages.notes.bulletindenotes', compact('classes', 'promotions', 'eleves', 'matieres', 'typean', 'classesg', 'current'));
    }

    public function configurerDecisions(Request $request)
    {

        // dd('yoyoyoyoyoyo');
        $data = $request->validate([
            'promotion'                => 'required',
            'intervals.non.*.min'     => 'required|numeric|between:0,100',
            'intervals.non.*.max'     => 'required|numeric|between:0,100',
            'intervals.non.*.libelle' => 'required|string',
            'intervals.doublant.*.min'     => 'required|numeric|between:0,100',
            'intervals.doublant.*.max'     => 'required|numeric|between:0,100',
            'intervals.doublant.*.libelle' => 'required|string',
        ]);

        /* dd($data); */
        // dd($request->all());

        // Montage du tableau de données
        $payload = ['promotion' => $data['promotion']];

        // Non-redoublants
        foreach (range(1, 5) as $i) {
            $payload["NouveauBorneI{$i}A"]  = $data['intervals']['non'][$i]['min'];
            $payload["NouveauBorneI{$i}B"]  = $data['intervals']['non'][$i]['max'];
            $payload["NouveauLibelleI{$i}"] = $data['intervals']['non'][$i]['libelle'];
        }

        // Redoublants
        foreach (range(1, 5) as $i) {
            $payload["AncienBorneI{$i}A"]    = $data['intervals']['doublant'][$i]['min'];
            $payload["AncienBorneI{$i}B"]    = $data['intervals']['doublant'][$i]['max'];
            $payload["AncienLibelleI{$i}"]   = $data['intervals']['doublant'][$i]['libelle'];
        }

        // Mise à jour ou création
        DecisionConfiguration::updateOrCreate(
            ['promotion' => $data['promotion']],
            $payload
        );


        // 1) Récupère les données initiales de la page (classes, promos…)
        $init = session()->get('bulletinInit', []);
        if (empty($init)) {
            // au cas où on arrive ici sans avoir d'init, on peut abort ou refetch
            abort(404, 'Données de configuration manquantes en session.');
        }

        // return redirect()->route('bulletindenotes');

        // 3) Redirige vers bulletindenotes (qui relira bulletinInit + decisions)

        return redirect()->route('bulletindenotes')
            ->with('success', 'Décisions bien enregistrées !');
    }


    public function getClassesByType(Request $request)
    {
        $libelleGroupe = $request->input('libelleGroupe');
        // Vérifier que le groupe est sélectionné
        if (!$libelleGroupe) {
            return response()->json(['error' => 'Aucun groupe sélectionné'], 400);
        }

        // Récupérer les classes avec le compte des élèves
        $classes = DB::table('classes_groupeclasse')
            ->join('classes', 'classes_groupeclasse.CODECLAS', '=', 'classes.CODECLAS')
            ->leftJoin('eleve', 'classes.CODECLAS', '=', 'eleve.CODECLAS')
            ->where('classes_groupeclasse.LibelleGroupe', $libelleGroupe)
            ->select(
                'classes.CODECLAS',
                DB::raw('COUNT(eleve.MATRICULE) as EFFECTIF')
            )
            ->groupBy('classes.CODECLAS')
            ->get();

        return response()->json($classes);
    }


    public function storebulletindenotes(Request $request)
    {

        dd($request->all());
        return redirect()->route('printbulletindenotes', $request->all());
    }

    public function optionsbulletindenotes(Request $request)
    {
        $option = $request->all();

        Session::put('option', $option);
        // dd($option);
    }


    private function extractTextFromRtf($rtfString)
    {
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



    // private function initialiserVariables($request)
    // {

    //     $option = Session::get('option');

    //     // dd($request);
    //     $this->periode = $request['periode'];
    //     $this->classesS = $request['selected_classes'];
    //     $this->bonifications = $request['bonification'];
    //     $this->bonificationType = $request['bonificationType'];
    //     // $this->option = $request['option'];
    //     // $this->annee = Parametre::getValeur('annee');
    //     $infoparamcontrat = Paramcontrat::first();
    //     $this->annee = $infoparamcontrat->anneencours_paramcontrat;
    //     // $this->entete = Parametre::getValeur('entete');
    //     $params2 = Params2::first();
    //     $typean = $params2->TYPEAN;
    //     $rtfContent = Params2::first()->EnteteBull;
    //     // $document = new Document($rtfContent);
    //     // $formatter = new HtmlFormatter();
    //     // $this->entete = $formatter->Format($rtfContent);
    //     // dd($this->entete);
    //     // $this->entete = $document->toHtml();
    //     // $this->entete = $rtfContent;    
    //     // $this->entete = Html::convert($rtfContent);    
    //     $this->entete = $this->extractTextFromRtf($rtfContent);
    //     // $this->totalSemestres = Parametre::getValeur('total_semestres');
    //     $this->classes = DB::table('eleve')->select('CODECLAS')->distinct()->get();
    // }

    // private function mettreAJourRangAnnuel()
    // {
    //     foreach ($this->classes as $classe) {
    //         $eleves = DB::table('eleve')
    //             ->where('CODECLAS', $classe->CODECLAS)
    //             ->orderByDesc('MAN', 'desc')
    //             ->get();

    //         $rang = 1;
    //         $precedentMAN = null;

    //         foreach ($eleves as $eleve) {
    //             $currentMAN = $eleve->MAN;
    //             if ($precedentMAN !== null && $currentMAN != $precedentMAN) {
    //                 $rang++;
    //             }
    //             DB::table('eleve')
    //                 ->where('MATRICULE', $eleve->MATRICULE)
    //                 ->update(['RANGA' => $rang]);
    //             $precedentMAN = $currentMAN;
    //         }
    //     }
    // }

    // private function calculerMoyennesAnnuelles()
    // {
    //     foreach ($this->classes as $classe) {
    //         $eleves = DB::table('eleve')
    //             // ->where('Classe', $classe)
    //             ->get();

    //         foreach ($eleves as $eleve) {
    //             $moyennes = [];
    //             for ($i = 1; $i <= 12; $i++) {
    //                 $colonne = 'MS' . $i;
    //                 if (Schema::hasColumn('eleve', $colonne)) {
    //                     $moyenne = $eleve->$colonne;
    //                     if ($moyenne !== null &&  $moyenne > 0) {
    //                         $moyennes[] = $moyenne;
    //                     }
    //                 }
    //             }

    //             if (count($moyennes) > 0) {
    //                 $moyenneAnnuelle = array_sum($moyennes) / count($moyennes);
    //                 DB::table('eleve')
    //                     ->where('MATRICULE', $eleve->MATRICULE)
    //                     ->update(['MAN' => $moyenneAnnuelle]);
    //             }
    //         }
    //     }
    // }

    // ********************************
    // private function appliquerBonifications()
    // {
    //     foreach ($this->classes as $classe) {
    //         $eleves = DB::table('eleve')
    //             ->where('Classe', $classe)
    //             ->get();

    //         foreach ($eleves as $eleve) {
    //             $moyenneAnnuelle = $eleve->MAN;

    //             switch ($this->bonifications['type']) {
    //                 case 'aucune':
    //                     break;

    //                 case 'integral':
    //                     $moyenneAnnuelle += $this->bonifications['valeur'];
    //                     break;

    //                 case 'intervalle':
    //                     if ($moyenneAnnuelle >= $this->bonifications['min'] && $moyenneAnnuelle <= $this->bonifications['max']) {
    //                         $moyenneAnnuelle += $this->bonifications['valeur'];
    //                     }
    //                     break;
    //             }

    //             DB::table('eleve')
    //                 ->where('Matricule', $eleve->Matricule)
    //                 ->update(['MAN' => $moyenneAnnuelle]);
    //         }
    //     }
    // }


    // private function calculerMoyennesSemestrielles($periode)
    // {
    //     foreach ($this->classes as $classe) {
    //         $eleves = DB::table('eleve')
    //             ->where('Classe', $classe)
    //             ->get();

    //         foreach ($eleves as $eleve) {
    //             $notes = DB::table('notes')
    //                 ->where('Matricule', $eleve->Matricule)
    //                 ->where('Semestre', $periode)
    //                 ->get();

    //             $totalNotes = 0;
    //             $totalCoef = 0;

    //             foreach ($notes as $note) {
    //                 $totalNotes += $note->Note * $note->Coef;
    //                 $totalCoef += $note->Coef;
    //             }

    //             if ($this->option['inclure_conduite']) {
    //                 $totalNotes += $eleve->Conduite * $this->option['coef_conduite'];
    //                 $totalCoef += $this->option['coef_conduite'];
    //             }

    //             if ($totalCoef > 0) {
    //                 $moyenneSemestrielle = $totalNotes / $totalCoef;
    //                 DB::table('eleve')
    //                     ->where('Matricule', $eleve->Matricule)
    //                     ->update(['MS' . $periode => $moyenneSemestrielle]);
    //             }
    //         }
    //     }
    // }

    // private function attribuerRangsSemestriels()
    // {
    //     foreach ($this->classes as $classe) {
    //         $eleves = DB::table('eleve')
    //             ->where('Classe', $classe)
    //             ->orderByDesc('MS' . $this->periode)
    //             ->get();

    //         $rang = 1;
    //         $precedenteMoyenne = null;

    //         foreach ($eleves as $eleve) {
    //             $currentMoyenne = $eleve->{'MS' . $this->periode};

    //             if ($precedenteMoyenne !== null && $currentMoyenne != $precedenteMoyenne) {
    //                 $rang++;
    //             }

    //             DB::table('eleve')
    //                 ->where('Matricule', $eleve->Matricule)
    //                 ->update(['RANG' . $this->periode => $rang]);

    //             $precedenteMoyenne = $currentMoyenne;
    //         }
    //     }
    // }

    // public function calculerMoyenne($request)
    // {
    //     $this->initialiserVariables($request);
    //     $this->mettreAJourRangAnnuel();
    //     $this->calculerMoyennesAnnuelles();
    //     $this->appliquerBonifications();
    //     $this->calculerMoyennesSemestrielles($this->periode);
    //     $this->attribuerRangsSemestriels();
    // }




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


    public function printimagefond(Request $request) {}


    /**
     * Retourne une chaîne style inline (font-family + font-size) en extrayant
     * la police et la taille depuis le contenu RTF.
     */
    private function getStyleFromRtf(string $rtfContent): string
    {
        // Taille (first \fsN) -> N is half-points in RTF
        $fontSizePx = 14; // fallback
        if (preg_match('/\\\\fs(\d+)/', $rtfContent, $m)) {
            $pt = ((int)$m[1]) / 2;            // points
            $fontSizePx = round($pt * 1.333);  // approx px
        }

        // Police : chercher la fonttbl et la première police référencée (\fX)
        $fontFamily = 'Arial, sans-serif';
        // Récupère toutes les définitions de font dans le fonttbl
        if (preg_match_all('/\\{\\\\f(\d+)\s+([^;\\}]+);\\}/i', $rtfContent, $fonts, PREG_SET_ORDER)) {
            // récupérer l'index de la première occurrence \fX utilisée dans le document
            if (preg_match('/\\\\f(\d+)/', $rtfContent, $m2)) {
                $usedIndex = $m2[1];
                foreach ($fonts as $f) {
                    if ((string)$f[1] === (string)$usedIndex) {
                        $fontFamily = trim($f[2]);
                        // add fallback generic
                        if (stripos($fontFamily, 'times') !== false) {
                            $fontFamily .= ', "Times New Roman", serif';
                        } else {
                            $fontFamily .= ', Arial, sans-serif';
                        }
                        break;
                    }
                }
            } else {
                // sinon prends la première définition trouvée
                $fontFamily = trim($fonts[0][2]) . ', Arial, sans-serif';
            }
        }

        return "font-family: {$fontFamily}; font-size: {$fontSizePx}px;";
    }



    public function printbulletindenotes(Request $request)
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('img/fonds'), $imageName);

            Imgbulletin::create([
                'nom_image' => $imageName
            ]);

            $image = $imageName;
        } else {
            $lastImage = Imgbulletin::latest()->first();
            $image = $lastImage ? $lastImage->nom_image : null;
        }


        $option = Session::get('option');
        $moyennesParClasseEtMatiere = [];
        $paramselection = $request->input('paramselection');
        $bonificationType = $request->input('bonificationType');
        $bonifications = $request->input('bonification');
        $msgEnBasBulletin = $request->input('msgEnBasBulletin');
        $periode = $request->input('periode');
        $conduite = $request->input('conduite');
        $eps = $request->input('eps');
        $nbabsence = $request->input('nbabsence');
        $classeSelectionne = $request->input('selected_classes');
        $interligne = $request->input('interligne', 7); // Valeur par défaut de 7mm
        $promo = Promo::all();
        $decisions = DecisionConfiguration::all();

        // --------------------------------
        $pondSem1Check = $request->input('pondSem1Check');
        $pondSem2Check = $request->input('pondSem2Check');
        $pondSem1 = intVal($request->input('pondSem1'));
        $pondSem2 = intVal($request->input('pondSem2'));

        $pondTrim1Check = $request->input('pondTrim1Check');
        $pondTrim2Check = $request->input('pondTrim2Check');
        $pondTrim3Check = $request->input('pondTrim3Check');
        $pondTrim1 = intVal($request->input('pondTrim1'));
        $pondTrim2 = intVal($request->input('pondTrim2'));
        $pondTrim3 = intVal($request->input('pondTrim3'));
        // dd($pondSem1Check, $pondSem2Check, $pondSem1, $pondSem2);
        //COnfgurer décisions en fonction de l'intervalles

        session()->put('conduite', $conduite);
        session()->put('eps', $eps);
        session()->put('nbabsence', $nbabsence);

        $params2 = Params2::first();
        $typean = $params2->TYPEAN;
        $rtfContent = Params2::first()->EnteteBull;
        // $entete = $this->extractTextFromRtf($rtfContent);

        // ancien code pour appliqué les style a lentete du bulletin

        // $rtfContent = Params2::first()->EnteteBull;
        // $document = new Document($rtfContent);
        // $formatter = new HtmlFormatter();
        // $enteteNonStyle = $formatter->Format($document);
        // $entete = '
        // <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
        //     <style>
        //         p { margin: 0; padding: 0; line-height: 1.2; }
        //         span { display: inline-block; }
        //     </style>
        //     ' . $enteteNonStyle . '
        // </div>
        // ';
        // dd($entete);

        $rtfContent = Params2::first()->EnteteBull;
    $document = new Document($rtfContent);
    $formatter = new HtmlFormatter();
    $enteteNonStyle = $formatter->Format($document);

    // build inline style from rtf
    $inlineStyle = $this->getStyleFromRtf($rtfContent);

    $entete = '
    <div style="text-align: center; line-height: 1.2; ' . $inlineStyle . '">
        <style>p { margin:0; padding:0; line-height:1.2 } span { display:inline-block; }</style>
        ' . $enteteNonStyle . '
    </div>';

        $logo = $params2->logoimage;

        // Conversion des données en Base64
        $logoBase64 = base64_encode($logo);

        // Définir le type MIME de l'image (adaptez-le en fonction de votre image)
        $mimeType = 'image/png';

        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours . '-' . $annesuivante;
        $clases = DB::table('eleve')->select('CODECLAS')->distinct()->get();

        // 



        // Récupérer la classe sélectionnée
        $codeClasse = $classeSelectionne;
        if (empty($codeClasse)) {
            // dd('vide');
            return redirect()->back()->with('success', 'Veuillez sélectionner une classe.');
        }

        // Filtrer le tableau en enlevant l'élément 'all'
        $classeSelectionne = array_filter($classeSelectionne, function ($value) {
            return $value !== 'all';
        });

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

                $countValidSubjects = 0;
                $totalNote          = 0;
                $totalCoef          = 0;

                foreach ($notes as $note) {
                    // Exclure les valeurs indésirables
                    // if ($note->MS !== null && $note->MS != -1 && $note->MS != 21 ) {
                    //     $totalNote += $note->MS * $note->COEF;
                    //     $totalCoef += $note->COEF;
                    // }

                    // Vérifier qu'au moins un devoir (DEV1 ou DEV2) est valide
                    $hasValidDevoir = (
                        isset($note->DEV1)
                        && $note->DEV1 !== null
                        && $note->DEV1 != -1
                        && $note->DEV1 != 21
                    ) || (
                        isset($note->DEV2)
                        && $note->DEV2 !== null
                        && $note->DEV2 != -1
                        && $note->DEV2 != 21
                    );

                    // On ne prend la matière en compte que si MS est valide ET qu'il y a au moins un devoir valide
                    if (
                        $note->MS !== null
                        && $note->MS != -1
                        && $note->MS != 21
                        && $hasValidDevoir
                    ) {
                        $totalNote += $note->MS * $note->COEF;
                        $totalCoef += $note->COEF;
                        $countValidSubjects++;
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
                // $moyennePeriode = ($totalCoef > 0) ? round($totalNote / $totalCoef, 2) : null;

                if ($countValidSubjects >= 4 && $totalCoef > 0) {
                    $moyennePeriode = round($totalNote / $totalCoef, 2);
                } else {
                    // Moins de 4 notes valides => pas de moyenne semestrielle
                    $moyennePeriode = 21;
                }
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

            // $sumWeighted = 0;
            // $totalWeights = 0;
            // if (isset($studentPeriods[1]) && $studentPeriods[1]['moyenne'] !== null) {
            //     $sumWeighted += $studentPeriods[1]['moyenne'] * $interligne;
            //     $totalWeights += $interligne;
            // }
            // if (isset($studentPeriods[2]) && $studentPeriods[2]['moyenne'] !== null) {
            //     $sumWeighted += $studentPeriods[2]['moyenne'] * $interligne;
            //     $totalWeights += $interligne;
            // }
            // if (isset($studentPeriods[3]) && $studentPeriods[3]['moyenne'] !== null) {
            //     $sumWeighted += $studentPeriods[3]['moyenne'] * $interligne;
            //     $totalWeights += $interligne;
            // }

            // $moyenneAnnuelle = ($totalWeights > 0) ? round($sumWeighted / $totalWeights, 2) : null;

            // calcule moyenne annuelle
            // Récupération des moyennes des périodes 1 et 2
            $moyenneP1 = isset($studentPeriods[1]['moyenne']) && $studentPeriods[1]['moyenne'] !== 21
                ? $studentPeriods[1]['moyenne']
                : null;
            $moyenneP2 = isset($studentPeriods[2]['moyenne']) && $studentPeriods[2]['moyenne'] !== 21
                ? $studentPeriods[2]['moyenne']
                : null;
            // Si on est en trimestres, on récupère aussi la période 3
            if ($typean == 2) {
                $moyenneP3 = isset($studentPeriods[3]['moyenne']) && $studentPeriods[3]['moyenne'] !== 21 ? $studentPeriods[3]['moyenne'] : null;
            } else {
                $moyenneP3 = 21; // Pas utilisé pour typean == 1
            }

            // Calcul de la moyenne annuelle pondérée (pour les semestres uniquement)
            if ($typean == 1) {
                // ——— Mode semestres (2 périodes) ———
                if ($moyenneP1 !== null  && $moyenneP1 !== 21 && $moyenneP2 !== null && $moyenneP2 !== 21) {
                    // dd($pondSem1Check, $pondSem2Check, $pondSem1, $pondSem2);

                    $moyenneAnnuelle = round((($pondSem2 * $moyenneP2) + ($pondSem1 * $moyenneP1)) / ($pondSem1 + $pondSem2), 2);

                } elseif ($moyenneP2 !== null  && $moyenneP2 !== 21) {
                    // Seule P2 existe
                    if (isset($pondSem2Check)) {
                        $moyenneAnnuelle = $moyenneP2;
                    } else {
                        $moyenneAnnuelle = 21;
                    }
                } else {
                    // Seule P1 existe
                    if (isset($pondSem1Check)) {
                        $moyenneAnnuelle = $moyenneP1;
                    } else {
                        $moyenneAnnuelle = 21;
                    }
                }
            } else {
                // ——— Mode trimestres (3 périodes) ———
                //   // On ne retient QUE les moyennes non-null
                //   $listeValides = [];
                //   if ($moyenneP1 !== null && $moyenneP1 !== 21) {
                //       $listeValides[] = $moyenneP1;
                //   }
                //   if ($moyenneP2 !== null && $moyenneP2 !== 21) {
                //       $listeValides[] = $moyenneP2;
                //   }
                //   if ($moyenneP3 !== null && $moyenneP3 !== 21) {
                //       $listeValides[] = $moyenneP3;
                //   }

                //   $countValides = count($listeValides);
                //   if ($countValides > 0) {
                //       // Si au moins une période valide, on fait la moyenne arithmétique des valeurs présentes
                //       $somme = array_sum($listeValides);
                //       $moyenneAnnuelle = round($somme / $countValides, 2);
                //   } else {
                //       // Aucune note sur les 3 périodes
                //       $moyenneAnnuelle = 21;
                //   }


                // Si P3 manquent toutes les deux, on fixe la moyenne annuelle à 21
                if (
                    // ($moyenneP2 === null || $moyenneP2 === 21)  && 
                    ($moyenneP3 === null || $moyenneP3 === 21)
                ) {
                    $moyenneAnnuelle = 21;
                } else {
                    // Initialisation des sommes pondérées
                    $sommePonderee = 0;
                    $sommePond     = 0;

                    // Période 1
                    if ($moyenneP1 !== null && $moyenneP1 !== 21) {
                        $sommePonderee += ($moyenneP1 * $pondTrim1);
                        $sommePond     += $pondTrim1;
                    }
                    // Période 2
                    if ($moyenneP2 !== null && $moyenneP2 !== 21) {
                        $sommePonderee += ($moyenneP2 * $pondTrim2);
                        $sommePond     += $pondTrim2;
                    }
                    // Période 3
                    if ($moyenneP3 !== null && $moyenneP3 !== 21) {
                        $sommePonderee += ($moyenneP3 * $pondTrim3);
                        $sommePond     += $pondTrim3;
                    }

                    // Calcul de la moyenne annuelle pondérée
                    if ($sommePond > 0) {
                        $moyenneAnnuelle = round($sommePonderee / $sommePond, 2);
                    } else {
                        // Cas théorique si aucune période valide (ne devrait pas se produire
                        // ici car P3 n'étaient pas toutes deux manquantes)
                        $moyenneAnnuelle = 21;
                    }

                    // dd($sommePonderee, $sommePond);

                    // dd($pondTrim1, $pondTrim2, $pondTrim3, $moyenneP1, $moyenneP2, $moyenneP3);

                }
            }


            // dd($moyenneP1,  $moyenneP2, $moyenneP3, $moyenneAnnuelle);

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

            // dd($resultatsA);
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
            // $resA['moyenneAnnuelle'] = $resA['moyenneAnnuelle'] ;
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

                // dd($data);

                // Mettre à jour l'élève dans la base de données
                Eleve::where('MATRICULE', $matricule)->update($data);
            }
        }


        unset($res);

        // Calcul des indicateurs de la classe à partir des élèves de la classe sélectionnée
        $elevesAA2 = Eleve::where('CODECLAS', $codeClasse)->get();
        $classesUnique = $elevesAA2->pluck('CODECLAS')->unique();

        foreach ($classesUnique as $codeClasse) {
            // Filtrer les élèves appartenant à la classe courante
            $elevesClasse = $elevesAA2->where('CODECLAS', $codeClasse);

            // Récupérer les moyennes par période en filtrant les valeurs null, -1 ou <= 0
            $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function ($value) {
                return $value !== null && $value !== -1 && $value > 0 && $value != 21;
            })->toArray();

            $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function ($value) {
                return $value !== null && $value !== -1 && $value > 0 && $value != 21;
            })->toArray();

            // Pour la période 3, on calcule seulement si TYPEAN n'est pas 1 (donc pour trimestres)
            if ($typean != 1) {
                $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function ($value) {
                    return $value !== null && $value !== -1 && $value > 0 && $value != 21;
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
            if ($eleve->MAN !== null && $eleve->MAN !== -1 && $eleve->MAN > 0 && $eleve->MAN != 21) {
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

            if ($periode === "1") {

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
                if ($bilan != -1 && $bilan !== null) {
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
                if ($bilan != -1 && $bilan !== null) {
                    $bilanScientifiqueTotal += $bilan;
                    $bilanScientifiqueCount++;
                }
            }

            // Calcul de la moyenne (évitez la division par zéro)
            $moyenneBilanScientifique = $bilanScientifiqueCount > 0 ? $bilanScientifiqueTotal / $bilanScientifiqueCount : null;

            // dd($eleve->MBILANS1, $eleve->MBILANS2, $eleve->MBILANS3);
            // dd($moyenneBilanScientifique);
            // CALCUL DU BILAN ANNUELLE DES MATIERES FONDAMENTALES 
            $bilanFondamentaleTotal = 0; // Somme des bilans littéraires valides
            $bilanFondamentaleCount = 0; // Compteur des bilans littéraires valides

            // Vérifiez et ajoutez chaque bilan Fondamentale
            $bilanFondamentales = [$eleve->MoyMatFond1, $eleve->MoyMatFond2, $eleve->MoyMatFond3];
            foreach ($bilanFondamentales as $bilan) {
                if ($bilan != -1 && $bilan !== null) {
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


            // recuperer la decision correspondante


            // dd($eleve);

            $classeElev = $eleve->CODECLAS;
            $CodePromo = Classes::where('CODECLAS', $classeElev)->first();
            $CODEPROMO = $CodePromo->CODEPROMO;
            // dd($CODEPROMO);
            $moyenneAnnuelle = $eleve->MAN;
            // $infoDecision = DecisionConfiguration::where('promotion', $CODEPROMO)->first();
            // // $NouveauBorneI1A = $infoDecision->NouveauBorneI1A;
            // // dd($infoDecision);

            // // On récupère la configuration pour la promotion
            // // $infoDecision = DecisionConfiguration::where('promotion', $CODEPROMO)->first();

            // // On choisit le préfixe selon le statut
            // if ($eleveA->STATUT == 0) {
            //     $prefix = 'Nouveau';
            // } elseif ($eleveA->STATUT == 1) {
            //     $prefix = 'Ancien';
            // } else {
            //     $prefix = null;
            // }

            // // On détermine le libellé
            // $decisionAnnuelle = null;
            // if ($prefix) {
            //     // Parcours des 5 intervalles
            //     for ($i = 1; $i <= 5; $i++) {
            //         // Construction dynamique du nom des propriétés
            //         $lowProp   = "{$prefix}BorneI{$i}A";
            //         $highProp  = "{$prefix}BorneI{$i}B";
            //         $labelProp = "{$prefix}LibelleI{$i}";

            //         $low   = floatval($infoDecision->$lowProp);
            //         $high  = floatval($infoDecision->$highProp);
            //         $moy   = floatval($moyenneAnnuelle);

            //         // Test inclusif : [borneA, borneB[
            //         if ($moy >= $low && $moy < $high) {
            //             $decisionAnnuelle = $infoDecision->$labelProp;
            //             break;
            //         }
            //         // Pour le dernier intervalle (qui va jusqu'à la borne B incluse)
            //         // if ($i === 5 && $moy >= $low && $moy <= $high) {
            //         //     $appreciationAnnuelle = $infoDecision->$labelProp;
            //         //     break;
            //         // }
            //     }
            // }


            $infoDecision = DecisionConfiguration::where('promotion', $CODEPROMO)->first();

            if (!$infoDecision) {
                // Pas de config trouvée : on peut définir un libellé par défaut
                $decisionAnnuelle = '......................................................................';
            } else {
                // Votre logique existante
                $prefix = $eleve->STATUT == 0 ? 'Nouveau'
                    : ($eleve->STATUT == 1 ? 'Ancien' : null);

                $decisionAnnuelle = null;
                if ($prefix) {
                    for ($i = 1; $i <= 5; $i++) {
                        $lowProp   = "{$prefix}BorneI{$i}A";
                        $highProp  = "{$prefix}BorneI{$i}B";
                        $labelProp = "{$prefix}LibelleI{$i}";

                        $low  = floatval($infoDecision->$lowProp);
                        $high = floatval($infoDecision->$highProp);
                        $moy  = floatval($moyenneAnnuelle);

                        // dd($lowProp , $highProp , $eleve);
                        if ($moy >= $low && $moy < $high) {
                            $decisionAnnuelle = $infoDecision->$labelProp;
                            break;
                        }
                        // pour l'intervalle final, si vous voulez inclure la borne haute :
                        if ($i === 5 && $moy >= $low && $moy <= $high) {
                            $decisionAnnuelle = $infoDecision->$labelProp;
                            break;
                        }
                    }
                }
                $decisionAnnuelle ??= '......................................................................';
            }
            $choixSemestre = request('periode');

            $resultatEleve = [
                'nom' => $eleve->NOM,
                'photo' => $eleve->PHOTO,  // Ajout de la photo
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
                'decisionAnnuelle'    => $decisionAnnuelle,
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
                'mentionDir' => $this->determineMentionDir($eleve, $params2, $choixSemestre),
                'matieres' => []
            ];

            // Grouper les notes par matière
            $notesParMatiere = $eleve->notes->groupBy('CODEMAT');

            foreach ($notesParMatiere as $codeMatiere => $notes) {
                // Vérifier si la matière est la conduite
                if ($codeMatiere == $conduite) {
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

                        if ($note->TEST < 21 && $note->TEST != null && $note->TEST != -1 && $note->TEST != 0) {
                            $test = $note->TEST;
                        } else {
                            $test = null;
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
                        'test' => null,
                        'moyenne_coeff' => $noteSpeciale * ($notes->first()->COEF),
                        'mentionProf' => $mentionMatSpecial, // Pas de mention pour la conduite ou EPS
                        'Typematiere' => 'CONDUITE', // Indication que c'est une matière conduite
                    ];
                    continue; // Passer à la matière suivante
                }

                // Vérification pour la matières EPS 

                if ($codeMatiere == $eps) {
                    // Récupération des notes d'interrogations valides
                    $interrosValides = $notes->map(function ($note) {
                        return collect([$note->INT1, $note->INT2, $note->INT3])
                            ->filter(function ($value) {
                                return $value !== null && $value != 21 && $value != 0;
                            });
                    })->flatten();

                    // Calcul de la moyenne des interrogations
                    $moyenneInterros = $interrosValides->avg();

                    // Récupération des notes de devoirs valides
                    $devoirsValides = $notes->map(function ($note) {
                        return collect([$note->DEV1, $note->DEV2, $note->DEV3])
                            ->filter(function ($value) {
                                return $value !== null && $value != 21 && $value != 0;
                            });
                    })->flatten();

                    // Combiner la moyenne des interros et les devoirs
                    $totalNotes = collect([$moyenneInterros])->merge($devoirsValides)->filter(); // Supprime les null
                    $moyenneEps = $totalNotes->avg(); // Calcul de la moyenne de la matière

                    // Stockage des moyennes
                    $moyennesParClasseEtMatiere[$eleve->CODECLAS][$codeMatiere][] = [
                        'eleve_id' => $eleve->MATRICULE,
                        'moyenne'  => $moyenneEps
                    ];
                    // }


                    // Déterminer la mention pour la matière
                    $mentionMaEps = $this->determineMention($moyenneEps, $params2);

                    // Extraire les notes de DEV1, DEV2, DEV3 individuellement
                    $noteDEV1 = $notes->first()->DEV1 ?? null;
                    $noteDEV2 = $notes->first()->DEV2 ?? null;
                    $noteDEV3 = $notes->first()->DEV3 ?? null;

                    if ($notes->first()->TEST < 21 && $notes->first()->TEST != null && $notes->first()->TEST != -1 && $notes->first()->TEST != 0) {
                        $test = $notes->first()->TEST;
                    } else {
                        $test = null;
                    }
                    // Ajouter cette matière au résultat avec les informations de bonification et les notes individuelles
                    $resultatEleve['matieres'][] = [
                        'code_matiere' => $codeMatiere,
                        'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? 'EPS',
                        'moyenne_interro' => $moyenneInterros,
                        'devoir1' => $noteDEV1, // Note individuelle DEV1
                        'devoir2' => $noteDEV2, // Note individuelle DEV2
                        'devoir3' => $noteDEV3, // Note individuelle DEV3
                        'test' => $test,
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

                    if ($notes->first()->TEST < 21 && $notes->first()->TEST != null && $notes->first()->TEST != -1 && $notes->first()->TEST != 0) {
                        $test = $notes->first()->TEST;
                    } else {
                        $test = null;
                    }

                    // Ajouter cette matière au résultat en tant que matière bonifiée avec notes individuelles
                    $resultatEleve['matieres'][] = [
                        'code_matiere' => $codeMatiere,
                        'nom_matiere' => $notes->first()->matiere->LIBELMAT ?? 'Matière Bonifiée',
                        'coefficient' => $notes->first()->COEF,
                        'moyenne_interro' => $moyenneInterros,
                        'devoir1' => $noteDEV1, // Note individuelle DEV1
                        'devoir2' => $noteDEV2, // Note individuelle DEV2
                        'devoir3' => $noteDEV3, // Note individuelle DEV3
                        'test' => $test,
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
                $moyenneInterro = ($note->MI ?? 21) < 21 ? $note->MI : 21;
                $moyenneDevoir = $nbDevoir > 0 ? $totalDevoir / $nbDevoir : 0;
                // $moyenneSur20 = $nbDevoir > 0 ? ($moyenneInterro + $totalDevoir) / ($nbDevoir + 1) : $moyenneInterro;
                // $moyenneCoeff = $totalCoeff > 0 ? $moyenneSur20 * $totalCoeff : 0;




                /*
        * Calcul de la moyenne générale sur 20 :
        * - Si aucun devoir n’existe ($nbDevoir == 0), on prend juste la moyenneInterro 
        *   (mais si moyenneInterro == 21, on renverra 0 pour ne pas compter l’absence).
        * - Si des devoirs existent ($nbDevoir > 0), on ajoute la moyenneInterro *seulement* 
        *   si elle est < 21. Sinon on ne l’inclut pas dans le calcul, 
        *   autrement dit on fait totalDevoir / nbDevoir.
        */
                if ($nbDevoir > 0) {
                    if ($moyenneInterro < 21) {
                        // On inclut l’interro : on ajoute 1 à nbDevoir et moyenneInterro au numérateur
                        $moyenneSur20 = ($moyenneInterro + $totalDevoir) / ($nbDevoir + 1);
                    } else {
                        // Interro absent (==21), on ne l’inclut pas dans le calcul
                        $moyenneSur20 = $totalDevoir / $nbDevoir;
                    }
                } else {
                    // Pas de devoirs :
                    //   - Si l’interro existe (<21), on prend sa valeur.
                    //   - Si l’interro vaut 21 (absence), on renvoie 0.
                    $moyenneSur20 = $moyenneInterro;
                }

                // dd($moyenneInterro);

                // Calcul de la moyenne pondérée (coeff)
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
            if ($eleve->MS1 !== null && $eleve->MS1 !== -1 && $eleve->MS1 !== 21 && $eleve->MS1 > 0) {
                $moyennesP1[] = $eleve->MS1;
            }
            // Période 2
            if ($eleve->MS2 !== null && $eleve->MS2 !== -1 && $eleve->MS2 !== 21 && $eleve->MS2 > 0) {
                $moyennesP2[] = $eleve->MS2;
            }
            // Période 3
            if ($eleve->MS3 !== null && $eleve->MS3 !== -1 && $eleve->MS3 !== 21 && $eleve->MS3 > 0) {
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
            if ($eleve->MS1 !== null && $eleve->MS1 !== -1 && $eleve->MS1 !== 21 && $eleve->MS1 > 0) {
                $totalP1 += $eleve->MS1;
                $countP1++;
            }
            // Période 2
            if ($eleve->MS2 !== null && $eleve->MS2 !== -1 && $eleve->MS2 !== 21 && $eleve->MS2 > 0) {
                $totalP2 += $eleve->MS2;
                $countP2++;
            }
            // Période 3
            if ($eleve->MS3 !== null && $eleve->MS3 !== -1 && $eleve->MS3 !== 21 && $eleve->MS3 > 0) {
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
            $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function ($value) {
                return $value !== null && $value !== -1 && $value > 0;
            })->toArray();

            $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function ($value) {
                return $value !== null && $value !== -1 && $value > 0;
            })->toArray();

            $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function ($value) {
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
                // 1) on ne garde que les moyennes valides
                $valeurs = array_filter(
                    $moyennes,
                    fn($item) => $item['moyenne'] !== 21 && $item['moyenne'] !== -1 && $item['moyenne'] !== null
                );

                // 2) on trie ces moyennes
                usort($valeurs, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);

                // si après filtre on n'a plus de valeurs, on passe au suivant
                if (empty($valeurs)) {
                    continue;
                }
                $maxMoyenne = max(array_column($valeurs, 'moyenne'));
                $minMoyenne = min(array_column($valeurs, 'moyenne'));

                $rang = 1;
                foreach ($valeurs as $index => $item) {
                    $rangAttribue = $index > 0 && $item['moyenne'] == $valeurs[$index - 1]['moyenne'] ? $rang : $index + 1;
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

                    // dd($minMoyenne);
                }
            }
        }

        // dd($resultats);


        // $data = compact('request', 'resultats', 'eleves', 'option', 'entete', 'typean', 'params2', 'logo', 'logoBase64', 'mimeType', 'interligne');

        //     // Générer le PDF depuis la vue 'pages.notes.printbulletindenotes'
        //     $pdf = PDF::loadView('pages.notes.printbulletindenotes', $data);

        //     // Définir un dossier d'archives (par exemple, public/archives/bulletins)
        //     $destinationPath = public_path('archives/bulletins');
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0755, true);
        //     }

        //     // Créer un nom de fichier unique
        //     $filename = 'bulletin_' . $codeClasse . '_' . date('Ymd_His') . '.pdf';

        //     // Sauvegarder le PDF dans le dossier
        //     $pdf->save($destinationPath . '/' . $filename);

        // dd($option);
        return view('pages.notes.printbulletindenotes', compact('request', 'resultats', 'eleves', 'option', 'entete', 'typean', 'params2', 'logo', 'logoBase64', 'mimeType', 'interligne', 'image'));
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

    private function determineMentionDir($eleve, $params2, int $semestre = 1)
    {
        // on choisit la bonne moyenne
        $moyenne = $semestre === 2 ? $eleve->MAN : $eleve->MS1;

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
            return ' ';
        }
    }


    // dd($resultats);

    // code pour une matiere de export vers educ master

    public function extrairenote()
    {

        $classes = Classes::get();
        $matieres = Matieres::get();
        $current = PeriodeSave::where('key', 'active')->value('periode');

        return view('pages.notes.extrairenote', [
            'classes' => $classes,
            'matieres' => $matieres,
            'selectedClasse' => request('classe'),
            'current' => $current,
        ]);
        // return view('pages.notes.extrairenote', compact('classes', 'matieres' ));
    }


    public function extractnote(Request $request)
    {

        $classes = Classes::get();
        $matieres = Matieres::get();


        $periode = $request->input('periode');
        $classe = $request->input('classe');
        $matiere = $request->input('matiere');
        $nomMatiere = Matieres::where('CODEMAT', $matiere)->first();

        $notes = Notes::with('eleve')
            ->where('CODECLAS', $classe)
            ->where('CODEMAT', $matiere)
            ->where('SEMESTRE', $periode)
            ->get()
            ->sortBy(function ($note) {
                return $note->eleve->NOM;
            });

        // Récupérer la valeur de typean depuis la table params2
        $typean = DB::table('params2')->value('typean'); // récupère la première valeur de 'typean'
        $periodLabel = ($typean == 1) ? 'Semestre' : 'Trimestre';

        // dd($notes);

        return view('pages.notes.extractnote', compact('classes', 'classe', 'matieres', 'periodLabel', 'periode', 'notes', 'nomMatiere'));
    }



    public function exportExcel(Request $request)
    {
        $periode = $request->input('periode');
        $classe  = $request->input('classe');
        $matiere = $request->input('matiere');
        $nomMatiere = Matieres::where('CODEMAT', $matiere)->first();
        $nomMat = $nomMatiere->LIBELMAT;

        $notes = Notes::with('eleve')
            ->where('CODECLAS', $classe)
            ->where('CODEMAT', $matiere)
            ->where('SEMESTRE', $periode)
            ->get()
            ->sortBy(function ($note) {
                return $note->eleve->NOM;
            });

        // Récupérer la valeur de typean depuis la table params2
        $typean = DB::table('params2')->value('typean'); // récupère la première valeur de 'typean'
        $periodLabel = ($typean == 1) ? 'Semestre' : 'Trimestre';


        // Récupérer les options d'export (1 = coché, 0 = décoché)
        $exportMoy = $request->input('exportMoy', 1);
        $exportDev1 = $request->input('exportDev1', 1);
        $exportDev2 = $request->input('exportDev2', 1);


        $notes = Notes::with('eleve')
            ->where('CODECLAS', $classe)
            ->where('CODEMAT', $matiere)
            ->where('SEMESTRE', $periode)
            ->get()
            ->sortBy(function ($note) {
                return $note->eleve->NOM;
            });


        // Récupérer la valeur de typean depuis la table params2
        $typean = DB::table('params2')->value('typean'); // récupère la première valeur de 'typean'
        $periodLabel = ($typean == 1) ? 'Semestre' : 'Trimestre';


        // Récupérer les options d'export (1 = coché, 0 = décoché)
        $exportMoy = $request->input('exportMoy', 1);
        $exportDev1 = $request->input('exportDev1', 1);
        $exportDev2 = $request->input('exportDev2', 1);

        // Création du nom de fichier incluant le nom de la classe.
        $fileName = $classe . '_' . $nomMat .  '.xlsx';

        return Excel::download(
            new NotesExport($nomMatiere, $notes, $classe, $periode, $periodLabel, $exportMoy, $exportDev1, $exportDev2),
            $fileName
        );
    }

    //   code pour selectionner plusieure matiere pour export vers educmaster

    public function getMatieresParClasse($codeclasse)
    {
        $matieres = DB::table('notes')
            ->join('matieres', 'notes.CODEMAT', '=', 'matieres.CODEMAT')
            ->where('notes.CODECLAS', $codeclasse)
            ->select('matieres.CODEMAT', 'matieres.LIBELMAT')
            ->distinct()
            ->get();

        return response()->json($matieres);
    }

    public function exportMulti(Request $request)
    {
        // Validation minimale
        $request->validate([
            'classe'   => 'required',
            'periode'  => 'required',
        ]);

        $classe  = $request->input('classe');
        $periode = $request->input('periode');
        // 'matieres' est un tableau
        $selectedMatieres = array_filter($request->input('matieres', []));

        // Si aucune matière n'est sélectionnée, rediriger avec erreur
        if (empty($selectedMatieres)) {
            return redirect()->back()->withErrors('Vous devez sélectionner au moins une matière.');
        }

        // Récupération de toutes les matières sélectionnées
        $matieres = \App\Models\Matieres::whereIn('CODEMAT', $selectedMatieres)->get();

        // Pour chaque matière, récupère les notes
        $result = [];
        foreach ($selectedMatieres as $matiereCode) {
            // On suppose que 'Notes' a les colonnes CODECLAS, CODEMAT, SEMESTRE
            $notes = \App\Models\Notes::with('eleve')
                ->where('CODECLAS', $classe)
                ->where('CODEMAT', $matiereCode)
                ->where('SEMESTRE', $periode)
                ->get();

            // Tri des notes par ordre alphabétique du nom de l'élève
            $notes = $notes->sortBy(function ($note) {
                return $note->eleve->NOM . ' ' . $note->eleve->PRENOM;
            });

            // On stocke les notes pour cette matière dans un tableau associatif
            $result[$matiereCode] = $notes;
        }

        // Récupération d'une information sur la période (ex: Semestre ou Trimestre)
        $typean = DB::table('params2')->value('typean');
        $periodLabel = ($typean == 1) ? 'Semestre' : 'Trimestre';

        // Passez les données à la vue de résultats
        return view('pages.notes.affichageextrairenote', compact('result', 'classe', 'periode', 'periodLabel', 'matieres'));
    }


    public function exportMultiExcel(Request $request)
    {
        $request->validate([
            'classe'   => 'required',
            'periode'  => 'required',
            'matieres' => 'array'
        ]);

        $classe  = $request->input('classe');
        $periode = $request->input('periode');
        $selectedMatieres = array_filter($request->input('matieres', []));

        if (empty($selectedMatieres)) {
            return redirect()->back()->withErrors('Veuillez sélectionner au moins une matière.');
        }

        // Récupérer les matières sélectionnées depuis la table Matieres
        $matieres = \App\Models\Matieres::whereIn('CODEMAT', $selectedMatieres)->get();

        // Pour chaque matière sélectionnée, récupérer les notes correspondantes de la table Notes
        $result = [];
        foreach ($selectedMatieres as $matiereCode) {
            $notes = \App\Models\Notes::with('eleve')
                ->where('CODECLAS', $classe)
                ->where('CODEMAT', $matiereCode)
                ->where('SEMESTRE', $periode)
                ->get()
                ->sortBy(function ($note) {
                    return $note->eleve->NOM . ' ' . $note->eleve->PRENOM;
                });
            if (!$notes->isEmpty()) {
                $result[$matiereCode] = $notes;
            }
        }

        // Récupérer le type d'année depuis la table params2 pour le libellé de période (si nécessaire)
        $typean = DB::table('params2')->value('typean');
        $periodLabel = ($typean == 1) ? 'Semestre' : 'Trimestre';

        // On peut inclure la classe et la période dans le nom du fichier si souhaité
        $fileName = $classe . '_' . $periodLabel . '_' . $periode . '_note' . '.xlsx';
        // $fileName = $classe . '_' . $periodLabel . '_' . $periode . '_' . time() . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new NotesClasseMultiExport($result, $matieres),
            $fileName
        );
    }











    public function importernote()
    {
        return view('pages.inscriptions.importenote');
    }


    // public function import(Request $request)
    // {
    //     if (!$request->hasFile('excelFile')) {
    //         return response()->json(['success' => false, 'message' => 'Aucun fichier sélectionné.']);
    //     }

    //     $file = $request->file('excelFile');

    //     try {
    //         $spreadsheet = IOFactory::load($file);
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $rows = $sheet->toArray();

    //         if (count($rows) < 2) {
    //             return response()->json(['success' => false, 'message' => 'Le fichier est vide ou mal formaté.']);
    //         }

    //         // Vider la table eleve
    //         DB::table('eleve')->truncate();

    //         $insertData = [];

    //         foreach ($rows as $index => $row) {
    //             if (($index === 0) || ($index === 1)) continue; // Ignorer la ligne des en-têtes

    //             $matricul = $row[0] ?? null;
    //             $nom      = $row[1] ?? null;
    //             $prenoms = $row[2] ?? null;
    //             if ($prenoms) {
    //                 // Nettoyer les espaces superflus et les caractères invisibles
    //                 $prenoms = trim($prenoms); // Enlever les espaces avant et après
    //                 $prenoms = preg_replace('/[\x00-\x1F\x7F]/', '', $prenoms); // Enlever les caractères invisibles

    //                 // Limiter la longueur à 500 caractères
    //                 if (strlen($prenoms) > 500) {
    //                     $prenoms = substr($prenoms, 0, 500);
    //                 }
    //             }
    //             $sexe     = isset($row[3]) ? ($row[3] === 'M' ? 1 : ($row[3] === 'F' ? 2 : null)) : null;
    //             $statut   = isset($row[4]) ? ($row[4] === 'R' ? 1 : ($row[4] === 'N' ? 0 : null)) : null;
    //             $classe   = $row[5] ?? null;

    //             // Ignorer les lignes sans matricule
    //             if (!$matricul) continue;

    //             // Vérifier si les colonnes existent dans la table `eleve`
    //             $columns = DB::getSchemaBuilder()->getColumnListing('eleve');

    //             // Préparer les données à insérer avec des UUID pour les colonnes guid_matri, guid_classe, guid_red
    //             $insertRow = [
    //                 'MATRICULEX' => $matricul,
    //                 'NOM'        => $nom,
    //                 'PRENOM'     => $prenoms,
    //                 'SEXE'       => $sexe,
    //                 'STATUT'     => $statut,
    //                 'CODECLAS'   => $classe,
    //             ];

    //             // Ajouter les valeurs uniques pour guid_matri, guid_classe, guid_red si ces colonnes existent
    //             if (in_array('guid_matri', $columns)) {
    //                 $insertRow['guid_matri'] = Str::uuid();
    //             }

    //             if (in_array('guid_classe', $columns)) {
    //                 $insertRow['guid_classe'] = Str::uuid();
    //             }

    //             if (in_array('guid_red', $columns)) {
    //                 $insertRow['guid_red'] = Str::uuid();
    //             }

    //             $insertData[] = $insertRow;
    //         }

    //         if (!empty($insertData)) {
    //             DB::table('eleve')->insert($insertData);
    //         }

    //         return response()->json(['success' => true, 'message' => 'Importation effectuée avec succès.']);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de l\'importation : ' . $e->getMessage()
    //         ]);
    //     }
    // }




// public function import(Request $request)
// {
//     // 1. Vérification du fichier
//     if (!$request->hasFile('excelFile')) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Aucun fichier sélectionné.'
//         ]);
//     }
//     $file = $request->file('excelFile');

//     try {
//         // 2. Lecture du fichier
//         $spreadsheet = IOFactory::load($file);
//         $sheet       = $spreadsheet->getActiveSheet();
//         $rows        = $sheet->toArray(null, true, false, true);

//         // $rows est un tableau indexé par lettres de colonnes : ['A'=>'matricule', 'B'=>'nom', ...]

//         if (count($rows) < 2) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Le fichier est vide ou ne contient que les en-têtes.'
//             ]);
//         }

//         // 3. On vide la table (vérifiez bien le nom de votre table : 'eleve' ou 'eleves')
//         DB::table('eleve')->truncate();

//         $insertData = [];

//         foreach ($rows as $rowIndex => $row) {
//             // On ne garde pas la première ligne d'en-têtes
//             if ($rowIndex === 1) {
//                 // Vous pouvez ici valider que les clés $row['A'], $row['B'], ... correspondent bien à vos attentes
//                 continue;
//             }

//             $matricule = trim($row['A'] ?? '');
//             $nom       = trim($row['B'] ?? '');
//             $prenoms   = trim($row['C'] ?? '');
//             $sexe      = strtoupper(trim($row['D'] ?? ''));
//             $statut    = strtoupper(trim($row['E'] ?? ''));
//             $classe    = trim($row['F'] ?? '');
//             $datenaisbrute    = trim($row['G'] ?? '');
//             $lieunais    = trim($row['H'] ?? '');

//             // Si pas de matricule, on ignore la ligne
//             if ($matricule === '') {
//                 continue;
//             }

//             // convertir la date
//             if ($datenaisbrute !== null && $datenaisbrute !== '') {
//                 // Si c'est un serial Excel (nombre), on convertit
//                 if (is_numeric($datenaisbrute)) {
//                     $dt = ExcelDate::excelToDateTimeObject((float) $datenaisbrute);
//                 } else {
//                     // En théorie on ne devrait jamais arriver ici puisque formattedData=false
//                     // Mais au cas où, on tente quand même dd/mm/YYYY
//                     $dt = \DateTime::createFromFormat('d/m/Y', $datenaisbrute);
//                 }

//                 if ($dt instanceof \DateTime) {
//                     $dateNaiss = $dt->format('Y-m-d');
//                 } else {
//                     return response()->json([
//                         'success' => false,
//                         'message' => "Date invalide à la ligne ".($rowIndex+1).": “{$datenaisbrute}”."
//                     ]);
//                 }
//             }

//             // Conversion sexe/statut en int
//             $sexeVal   = ($sexe === 'M' ? 1 : ($sexe === 'F' ? 2 : null));
//             $statutVal = ($statut === 'R' ? 1 : ($statut === 'N' ? 0 : null));

//             // Construire la ligne à insérer
//             $rowToInsert = [
//                 'MATRICULEX' => $matricule,
//                 'NOM'        => $nom,
//                 'PRENOM'     => $prenoms,
//                 'SEXE'       => $sexeVal,
//                 'STATUT'     => $statutVal,
//                 'CODECLAS'   => $classe,
//                 'DATENAIS'   => $dateNaiss,
//                 'LIEUNAIS'   => $lieunais,
//             ];

//             // Ajout des UUID si les colonnes existent
//             $columns = DB::getSchemaBuilder()->getColumnListing('eleve');
//             if (in_array('guid_matri', $columns))  $rowToInsert['guid_matri']  = Str::uuid();
//             if (in_array('guid_classe', $columns)) $rowToInsert['guid_classe'] = Str::uuid();
//             if (in_array('guid_red', $columns))    $rowToInsert['guid_red']    = Str::uuid();

//             $insertData[] = $rowToInsert;
//         }

//         // 4. Si aucune donnée à insérer, on renvoie une erreur
//         if (empty($insertData)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Aucune ligne valide trouvée dans le fichier. Vérifiez le format et les en-têtes.'
//             ]);
//         }

//         // 5. Insertion en base
//         DB::table('eleve')->insert($insertData);

//         return response()->json([
//             'success' => true,
//             'message' => 'Importation effectuée avec succès (' . count($insertData) . ' élèves).'
//         ]);
//     } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
//         // Erreur de parsing
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur de lecture du fichier Excel : ' . $e->getMessage()
//         ]);
//     } catch (\Exception $e) {
//         // Toute autre erreur
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur lors de l\'importation : ' . $e->getMessage()
//         ]);
//     }
// }

  public function import(Request $request)
{
        if (!$request->hasFile('excelFile')) {
            return response()->json(['success' => false, 'message' => 'Aucun fichier sélectionné.']);
        }

        $file = $request->file('excelFile');

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) < 2) {
                return response()->json(['success' => false, 'message' => 'Le fichier est vide ou mal formaté.']);
            }

            // Vider la table eleve
            DB::table('eleve')->truncate();

            $insertData = [];

            foreach ($rows as $index => $row) {
                if (($index === 0) || ($index === 1)) continue; // Ignorer la ligne des en-têtes

                $matricul = $row[0] ?? null;
                $nom      = $row[1] ?? null;
                $prenoms = $row[2] ?? null;
               
                if ($prenoms) {
                    // Nettoyer les espaces superflus et les caractères invisibles
                    $prenoms = trim($prenoms); // Enlever les espaces avant et après
                    $prenoms = preg_replace('/[\x00-\x1F\x7F]/', '', $prenoms); // Enlever les caractères invisibles

                    // Limiter la longueur à 500 caractères
                    if (strlen($prenoms) > 500) {
                        $prenoms = substr($prenoms, 0, 500);
                    }
                }
                $sexe     = isset($row[3]) ? ($row[3] === 'M' ? 1 : ($row[3] === 'F' ? 2 : null)) : null;
                $statut   = isset($row[4]) ? ($row[4] === 'R' ? 1 : ($row[4] === 'N' ? 0 : null)) : null;
                $classe   = $row[5] ?? null;
                $lieunais = $row[6] ?? null;
                $datenais = $row[7] ?? null;
              

                // Ignorer les lignes sans matricule
                if (!$matricul) continue;

                // Vérifier si les colonnes existent dans la table `eleve`
                $columns = DB::getSchemaBuilder()->getColumnListing('eleve');

                // Préparer les données à insérer avec des UUID pour les colonnes guid_matri, guid_classe, guid_red
                $insertRow = [
                    'MATRICULEX' => $matricul,
                    'NOM'        => $nom,
                    'PRENOM'     => $prenoms,
                    'SEXE'       => $sexe,
                    'STATUT'     => $statut,
                    'CODECLAS'   => $classe,
                    'LIEUNAIS'   =>$lieunais,
                    'DATENAIS' => $datenais,
                ];

                // Ajouter les valeurs uniques pour guid_matri, guid_classe, guid_red si ces colonnes existent
                if (in_array('guid_matri', $columns)) {
                    $insertRow['guid_matri'] = Str::uuid();
                }

                if (in_array('guid_classe', $columns)) {
                    $insertRow['guid_classe'] = Str::uuid();
                }

                if (in_array('guid_red', $columns)) {
                    $insertRow['guid_red'] = Str::uuid();
                }

                $insertData[] = $insertRow;
            }

            if (!empty($insertData)) {
                DB::table('eleve')->insert($insertData);
            }

            return response()->json(['success' => true, 'message' => 'Importation effectuée avec succès.']);
        } catch (\Exception $e) {
        // 1) Vérification du fichier
        if (! $request->hasFile('excelFile')) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier sélectionné.'
            ]);
        }
        $file = $request->file('excelFile');

        // 2) Chargement du classeur et lecture “raw” (formattedData=false)
        $spreadsheet = IOFactory::load($file);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(
            null,   // valeur par défaut
            true,   // calculateFormulas
            false,  // formattedData = false
            true    // indexByColumn = true, on aura ['A'=>…, 'B'=>…]
        );

        // 3) Si moins de 2 lignes (seulement l’en-tête), on stoppe
        if (count($rows) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Le fichier ne contient que les en-têtes ou est vide.'
            ]);
        }

        // 4) Détection de tous les doublons de matricule
        $linesByMatricule = [];
        foreach ($rows as $idx => $row) {
            if ($idx === 1) continue;  // on saute la ligne d’en-tête  
            $matricule = trim($row['A'] ?? '');
            if ($matricule === '') continue;
            $linesByMatricule[$matricule][] = $idx;
        }

        // 5) Construction du tableau d’erreurs pour chaque matricule dupliqué
        $errors = [];
        foreach ($linesByMatricule as $matricule => $lines) {
            if (count($lines) > 1) {
                if (count($lines) === 2) {
                    [$l1, $l2] = $lines;
                    $errors[] = "Ligne {$l1} et {$l2} : matricule « {$matricule} » en double.";
                } else {
                    $last = array_pop($lines);
                    $allButLast = implode(', ', $lines);
                    $errors[] = "Ligne {$allButLast} et {$last} : matricule « {$matricule} » en double.";
                }
            }
        }

        // 6) Si on a détecté des doublons, on renvoie sans toucher à la BDD
        if (! empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Des doublons ont été trouvés dans le fichier.',
                'errors'  => $errors
            ]);
        }

        // 7) Pas de doublons → on prépare l’insertion
        $insertData = [];
        foreach ($rows as $idx => $row) {
            if ($idx === 1) continue;

            $matricule = trim($row['A'] ?? '');
            if ($matricule === '') {
                // on peut choisir d’ajouter une erreur ici…
                continue;
            }

            // Lecture des autres colonnes
            $nom     = trim($row['B'] ?? '');
            $prenoms = trim($row['C'] ?? '');
            $sexeRaw = strtoupper(trim($row['D'] ?? ''));
            $statutRaw = strtoupper(trim($row['E'] ?? ''));
            $classe  = trim($row['F'] ?? '');
            $dateRaw = $row['G'] ?? '';
            $lieu    = trim($row['H'] ?? '');

            // Conversion du sexe/statut
            $sexe   = ($sexeRaw === 'M' ? 1 : ($sexeRaw === 'F' ? 2 : null));
            $statut = ($statutRaw === 'R' ? 1 : ($statutRaw === 'N' ? 0 : null));

            // Conversion de la date Excel (serial ou texte d/m/Y)
            $dateNaiss = null;
            if ($dateRaw !== '' && $dateRaw !== null) {
                if (is_numeric($dateRaw)) {
                    $dt = ExcelDate::excelToDateTimeObject((float)$dateRaw);
                } else {
                    $dt = \DateTime::createFromFormat('d/m/Y', $dateRaw);
                }
                if (! $dt) {
                    return response()->json([
                        'success' => false,
                        'message' => "Date invalide à la ligne {$idx} : “{$dateRaw}”."
                    ]);
                }
                $dateNaiss = $dt->format('Y-m-d');
            }

            // Construction du tableau pour l’insertion
            $rowToInsert = [
                'MATRICULEX' => $matricule,
                'NOM'        => $nom,
                'PRENOM'     => $prenoms,
                'SEXE'       => $sexe,
                'STATUT'     => $statut,
                'CODECLAS'   => $classe,
                'DATENAIS'   => $dateNaiss,
                'LIEUNAIS'   => $lieu,
            ];

            // Génération d’UUID si les colonnes existent
            $cols = DB::getSchemaBuilder()->getColumnListing('eleve');
            if (in_array('guid_matri',  $cols)) $rowToInsert['guid_matri']  = Str::uuid();
            if (in_array('guid_classe',$cols)) $rowToInsert['guid_classe'] = Str::uuid();
            if (in_array('guid_red',    $cols)) $rowToInsert['guid_red']    = Str::uuid();

            $insertData[] = $rowToInsert;
        }

        // 8) On vide la table et on insère tous les élèves d’un coup
        DB::table('eleve')->truncate();
        DB::table('eleve')->insert($insertData);

        return response()->json([
            'success' => true,
            'message' => 'Importation réussie de '.count($insertData).' élèves.'
        ]);
    }
}





    public function archiveBulletin(Request $request)
    {
        $data = $request->json()->all();
        $pdfDataUri = $data['pdf'] ?? null;
        $filename = $data['filename'] ?? 'bulletin.pdf';
        $classCode = $data['class'] ?? 'default';

        if ($pdfDataUri) {
            $parts = explode(',', $pdfDataUri);
            $pdfBase64 = end($parts);
            $pdfContent = base64_decode($pdfBase64);

            // Créer un dossier pour la classe dans public/archives/bulletins
            $destinationPath = public_path('archives/bulletins/' . $classCode);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            file_put_contents($destinationPath . '/' . $filename, $pdfContent);

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Aucun PDF reçu'], 400);
    }


    //Exportation vers le fichier excel

    public function exporternote()
    {
        return view('pages.inscriptions.exporternote');
    }


   public function getEleves()
{
    $eleves = DB::table('eleve')
        ->select('MATRICULEX', 'NOM', 'PRENOM', 'DATENAIS', 'LIEUNAIS', 'STATUT', 'SEXE', 'CODECLAS')
        ->orderBy('CODECLAS')
        ->get()
        ->groupBy('CODECLAS'); // regroupement par CODECLAS

    return view('pages.inscriptions.exporternoteexcel', compact('eleves'));
}
                                                                                                                                                                              

}
