<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Classe;
use App\Models\Salles;
use App\Models\Groupeclasse;
use App\Models\Matiere;
use App\Models\Agent;
use App\Models\Cours;

class EmploidutempsController extends Controller
{
    public function configsalles()
    {
        $classes = Classe::select('CODECLAS', 'CODESALLE', 'VOLANTE')->get();
        $salles  = Salles::select('CODESALLE', 'LOCALISATION')
                         ->orderBy('CODESALLE')
                         ->get();

        return view('pages.GestionPersonnel.configsalles', compact('classes', 'salles'));
    }

    /**
     * Associer chaque classe sans salle au code de la classe (CODESALLE = CODECLAS).
     */
    public function assignClassesToSalles(Request $request)
    {
        // Récupère les classes sans salle (avant la mise à jour)
        $classesToUpdate = Classe::whereNull('CODESALLE')
                                ->orWhere('CODESALLE', '')
                                ->get(['CODECLAS']);

        if ($classesToUpdate->isEmpty()) {
            return redirect()->route('configsalles')
                            ->with('info', 'Aucune classe sans salle à mettre à jour.');
        }

        // Normaliser les codes (MAJUSCULES, trim)
        $codes = $classesToUpdate->pluck('CODECLAS')
                                ->map(fn($c) => strtoupper(trim($c)))
                                ->unique()
                                ->filter()
                                ->values();

        DB::transaction(function () use ($codes) {
            // 1) Mettre CODESALLE = CODECLAS pour les classes
            // Ici on mettra la valeur brute CODECLAS ; si tu veux forcer MAJUSCULE sur la colonne, tu peux utiliser DB::raw('UPPER(CODECLAS)') selon ton SGBD.
            Classe::whereNull('CODESALLE')
                ->orWhere('CODESALLE', '')
                ->update([
                    'CODESALLE' => DB::raw('CODECLAS')
                ]);

            // 2) Créer en base les enregistrements manquants dans Salles
            foreach ($codes as $code) {
                // Normaliser ici aussi : on enregistre en majuscules
                $code = strtoupper($code);
                Salles::firstOrCreate(
                    ['CODESALLE' => $code],
                    ['LOCALISATION' => null]
                );
            }
        });

        return redirect()->route('configsalles')
                        ->with('success', 'Classes mises à jour et salles créées si nécessaire.');
    }

    public function toggleVolante(Request $request)
    {
        $request->validate([
            'codeclas' => 'required|string',
            'value'    => 'nullable|in:0,1'
        ]);

        $code = $request->codeclas;
        $explicit = $request->has('value') ? (int)$request->value : null;

        $classe = Classe::where('CODECLAS', $code)->first();

        if (! $classe) {
            return response()->json(['success' => false, 'message' => 'Classe introuvable.'], 404);
        }

        $current = $classe->VOLANTE;

        // Détecter format stocké et calculer nouvelle valeur
        $isBooleanLike = is_bool($current) || is_numeric($current) || in_array($current, [0,1,'0','1'], true);

        if ($explicit !== null) {
            // on définit la valeur explicitement
            if ($isBooleanLike) {
                $new = $explicit ? 1 : 0;
            } else {
                $new = $explicit ? 'OUI' : 'NON';
            }
        } else {
            // on inverse la valeur
            if ($isBooleanLike) {
                $new = ($current ? 0 : 1);
            } else {
                $new = (strtoupper($current) === 'OUI' || strtoupper($current) === 'YES') ? 'NON' : 'OUI';
            }
        }

        // Mise à jour
        $classe->VOLANTE = $new;
        $classe->save();

        return response()->json([
            'success' => true,
            'new'     => $new,
        ]);
    }

    /**
     * Créer une nouvelle salle
     */
    public function createSalle(Request $request)
    {
        try {
            Log::info('CreateSalle called with data:', $request->all());
            
            $request->validate([
                'codesalle'   => 'required|string|max:50|unique:salles,CODESALLE',
                'localisation'=> 'nullable|string|max:255',
            ], [
                'codesalle.required' => 'Le code de salle est obligatoire.',
                'codesalle.unique'   => 'Ce code de salle existe déjà.',
            ]);

            Log::info('Validation passed');

            $salle = Salles::create([
                'CODESALLE'   => strtoupper(trim($request->codesalle)),
                'LOCALISATION'=> trim($request->localisation) ?: null,
            ]);

            Log::info('Salle created:', $salle->toArray());

            return redirect()->route('configsalles')->with('success', 'Salle ajoutée avec succès.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating salle:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erreur lors de la création de la salle: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mettre à jour une salle (renommer + localisation).
     * Attendu : input 'codesalle' => ancien code, input 'new_codesalle' => nouveau code
     */
    public function updateSalle(Request $request)
    {
        $request->validate([
            'codesalle'     => 'required|exists:salles,CODESALLE',
            'new_codesalle' => 'required|string|max:50|unique:salles,CODESALLE,' . $request->codesalle . ',CODESALLE',
            'localisation'  => 'nullable|string|max:255',
        ], [
            'new_codesalle.required' => 'Le nouveau code de salle est obligatoire.',
            'new_codesalle.unique'   => 'Ce code de salle est déjà utilisé.',
        ]);

        $old = $request->codesalle;
        $new = strtoupper(trim($request->new_codesalle));
        $loc = trim($request->localisation) ?: null;

        DB::transaction(function () use ($old, $new, $loc) {
            // Mettre à jour la table salles
            Salles::where('CODESALLE', $old)->update([
                'CODESALLE'   => $new,
                'LOCALISATION'=> $loc,
            ]);

            // Si des classes faisaient référence à l'ancien code de salle, on les met à jour aussi
            Classe::where('CODESALLE', $old)->update([
                'CODESALLE' => $new,
            ]);
        });

        return redirect()->route('configsalles')->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Supprimer une salle (si non utilisée)
     */
    public function deleteSalle(Request $request)
    {
        $request->validate([
            'codesalle' => 'required|exists:salles,CODESALLE',
        ]);

        $code = $request->codesalle;

        if (Classe::where('CODESALLE', $code)->exists()) {
            return redirect()->route('configsalles')
                             ->with('error', 'Impossible de supprimer cette salle : elle est utilisée par une ou plusieurs classes.');
        }

        Salles::where('CODESALLE', $code)->delete();

        return redirect()->route('configsalles')->with('success', 'Salle supprimée avec succès.');
    }

    public function saisiremploitemps()
    {
        $classesg = Groupeclasse::select('LibelleGroupe')->distinct()->get();
        $classes = Classe::all();
        $matieres = Matiere::select('CODEMAT', 'LIBELMAT')->get();
        $salles = Salles::all();
        $agents = Agent::where('POSTE', 'Enseignant')->get();
        return view('pages.GestionPersonnel.saisiremploitemps', compact('classesg', 'classes', 'matieres', 'agents', 'salles'));
    }

        public function getClassesByGroupe(Request $request)
    {
        $libelleGroupe = $request->get('groupe');
        
        if (!$libelleGroupe) {
            return response()->json(['error' => 'Groupe non spécifié'], 400);
        }

        $classes = DB::table('classes_groupeclasse')
            ->join('classes', 'classes_groupeclasse.CODECLAS', '=', 'classes.CODECLAS')
            ->where('classes_groupeclasse.LibelleGroupe', $libelleGroupe)
            ->select('classes.CODECLAS')
            ->distinct()
            ->orderBy('classes.CODECLAS')
            ->get();

        return response()->json($classes);
    }

    /**
     * Enregistrer un cours dans l'emploi du temps
     */
    public function storeCours(Request $request)
    {
        try {
            Log::info('StoreCours called with data:', $request->all());

            // Validation des données
            $validator = Validator::make($request->all(), [
                'classe' => 'required|string|exists:classes,CODECLAS',
                'jour' => 'required|string|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
                'matiere' => 'required|integer|exists:matieres,CODEMAT',
                'heureDebut' => 'required|string',
                'heureFin' => 'required|string',
                'salle' => 'required|string|exists:salles,CODESALLE',
                'professeur' => 'required|integer|exists:agent,MATRICULE'
            ], [
                'classe.required' => 'La classe est obligatoire.',
                'classe.exists' => 'La classe sélectionnée n\'existe pas.',
                'jour.required' => 'Le jour est obligatoire.',
                'jour.in' => 'Le jour sélectionné n\'est pas valide.',
                'matiere.required' => 'La matière est obligatoire.',
                'matiere.exists' => 'La matière sélectionnée n\'existe pas.',
                'heureDebut.required' => 'L\'heure de début est obligatoire.',
                'heureFin.required' => 'L\'heure de fin est obligatoire.',
                'salle.required' => 'La salle est obligatoire.',
                'salle.exists' => 'La salle sélectionnée n\'existe pas.',
                'professeur.required' => 'Le professeur est obligatoire.',
                'professeur.exists' => 'Le professeur sélectionné n\'existe pas.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Préparer les données
            $jourNumber = Cours::getJourNumber($request->jour);
            $heure = Cours::formatHeure($request->heureDebut, $request->heureFin);
            
            // Vérifier les conflits
            $conflict = Cours::hasConflict(
                $jourNumber,
                $heure,
                $request->professeur,
                $request->salle,
                $request->classe
            );

            if ($conflict) {
                $messages = [
                    'professeur' => 'Ce professeur a déjà un cours à cette heure.',
                    'salle' => 'Cette salle est déjà occupée à cette heure.',
                    'classe' => 'Cette classe a déjà un cours à cette heure.'
                ];

                return response()->json([
                    'success' => false,
                    'message' => $messages[$conflict]
                ], 409);
            }

            // Générer les GUIDs
            $guid = uniqid('cours_', true);
            $guid_classe = uniqid('classe_', true);
            $guid_mat = uniqid('mat_', true);
            $guid_agent = uniqid('agent_', true);

            // Obtenir l'année académique actuelle (vous pouvez ajuster selon votre logique)
            $anneeAcademique = date('Y') . '-' . (date('Y') + 1);

            // Créer le cours
            $cours = Cours::create([
                'JOUR' => $jourNumber,
                'HEURE' => $heure,
                'CODECLAS' => $request->classe,
                'CODEMAT' => $request->matiere,
                'MATRICULE' => $request->professeur,
                'CODESALLE' => $request->salle,
                'libre' => 1,
                'Typeenreg' => 'MANUEL',
                'guid' => $guid,
                'guid_classe' => $guid_classe,
                'SiTE' => '',
                'guid_mat' => $guid_mat,
                'guid_agent' => $guid_agent,
                'anneeacademique' => $anneeAcademique
            ]);

            Log::info('Cours created successfully:', $cours->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Cours enregistré avec succès.',
                'data' => $cours
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing cours:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du cours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir l'emploi du temps d'une classe
     */
    public function getEmploiTemps(Request $request)
    {
        $classe = $request->get('classe');
        
        if (!$classe) {
            return response()->json(['error' => 'Classe non spécifiée'], 400);
        }

        $cours = Cours::with(['matiere', 'agent', 'salle'])
            ->where('CODECLAS', $classe)
            ->orderBy('JOUR')
            ->orderBy('HEURE')
            ->get();

        return response()->json($cours);
    }

    public function emploidutempsautomatique()
    {
        return view('pages.GestionPersonnel.Emploidutempsautomatique');
    }

    public function configquotahoraires()
    {
        return view('pages.GestionPersonnel.configquotahoraires');
    }

        public function general(Request $request)
    {
        try {
            // Récupération de toutes les classes pour le sélecteur
            $classes = DB::table('classes')
                ->select('CODECLAS', 'LIBELCLAS')
                ->orderBy('LIBELCLAS')
                ->get();

            // Récupération des cours avec filtrage optionnel par classe
            $query = DB::table('cours')
                ->leftJoin('classes', 'cours.CODECLAS', '=', 'classes.CODECLAS')
                ->leftJoin('matieres', 'cours.CODEMAT', '=', 'matieres.CODEMAT')
                ->leftJoin('agent', 'cours.MATRICULE', '=', 'agent.MATRICULE')
                ->select(
                    'cours.JOUR',
                    'cours.HEURE',
                    'cours.CODECLAS',
                    'cours.CODEMAT',
                    'cours.MATRICULE',
                    'cours.CODESALLE',
                    'classes.LIBELCLAS',
                    'matieres.LIBELMAT',
                    'agent.NOM',
                    'agent.PRENOM'
                )
                ->whereNotNull('cours.JOUR')
                ->whereNotNull('cours.HEURE');

            // Filtrage par classe si sélectionnée
            $classeSelectionnee = $request->get('classe');
            
            // Si aucune classe n'est sélectionnée ou "toutes" est sélectionné, ne pas récupérer de cours
            if (!$classeSelectionnee || $classeSelectionnee === 'toutes') {
                $emplois = collect([]);
            } else {
                $emplois = $query->where('cours.CODECLAS', $classeSelectionnee)
                    ->orderBy('cours.JOUR')
                    ->orderBy('cours.HEURE')
                    ->get();
            }

            // Structure horaire et jours
            $jours = [
                1 => 'Lundi', 
                2 => 'Mardi', 
                3 => 'Mercredi', 
                4 => 'Jeudi', 
                5 => 'Vendredi', 
                6 => 'Samedi'
            ];
            
            // Créneaux horaires standards
            $heures = [
                '07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', 
                '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', 
                '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00'
            ];

            return view('pages.GestionPersonnel.emploi_general', compact('emplois', 'jours', 'heures', 'classes', 'classeSelectionnee'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'emploi du temps: ' . $e->getMessage());
            
            return view('pages.GestionPersonnel.emploi_general', [
                'emplois' => collect([]),
                'jours' => [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'],
                'heures' => ['07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00'],
                'classes' => collect([]),
                'classeSelectionnee' => null
            ])->with('error', 'Erreur lors du chargement de l\'emploi du temps.');
        }
    }

    /**
     * Exporter l'emploi du temps en Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $classe = $request->get('classe');
            
            if (!$classe) {
                return redirect()->route('emploidutempsgeneral')->with('error', 'Veuillez sélectionner une classe.');
            }

            // Récupération des cours
            $emplois = DB::table('cours')
                ->leftJoin('classes', 'cours.CODECLAS', '=', 'classes.CODECLAS')
                ->leftJoin('matieres', 'cours.CODEMAT', '=', 'matieres.CODEMAT')
                ->leftJoin('agent', 'cours.MATRICULE', '=', 'agent.MATRICULE')
                ->select(
                    'cours.JOUR',
                    'cours.HEURE',
                    'cours.CODECLAS',
                    'classes.LIBELCLAS',
                    'matieres.LIBELMAT',
                    'agent.NOM',
                    'agent.PRENOM',
                    'cours.CODESALLE'
                )
                ->where('cours.CODECLAS', $classe)
                ->whereNotNull('cours.JOUR')
                ->whereNotNull('cours.HEURE')
                ->orderBy('cours.JOUR')
                ->orderBy('cours.HEURE')
                ->get();

            $jours = [
                1 => 'Lundi', 
                2 => 'Mardi', 
                3 => 'Mercredi', 
                4 => 'Jeudi', 
                5 => 'Vendredi', 
                6 => 'Samedi'
            ];
            
            $heures = [
                '07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', 
                '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', 
                '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00'
            ];

            // Préparer les données pour Excel
            $data = [];
            
            // En-tête
            $header = ['Horaires'];
            foreach ($jours as $jour) {
                $header[] = $jour;
            }
            $data[] = $header;

            // Construire le tableau
            foreach ($heures as $heure) {
                $row = [$heure];
                
                foreach ($jours as $jourNum => $jourNom) {
                    $coursCell = '';
                    
                    foreach ($emplois as $cours) {
                        if ((int)$cour->JOUR == (int)$jourNum) {
                            $coursHeure = trim($cours->HEURE);
                            $creneauHeure = trim($heure);
                            
                            if (strpos($coursHeure, '-') !== false && strpos($creneauHeure, '-') !== false) {
                                list($coursDebut, $coursFin) = explode('-', $coursHeure);
                                list($creneauDebut, $creneauFin) = explode('-', $creneauHeure);
                                
                                $coursDebutMin = (int)substr($coursDebut, 0, 2) * 60 + (int)substr($coursDebut, 3, 2);
                                $coursFinMin = (int)substr($coursFin, 0, 2) * 60 + (int)substr($coursFin, 3, 2);
                                $creneauDebutMin = (int)substr($creneauDebut, 0, 2) * 60 + (int)substr($creneauDebut, 3, 2);
                                $creneauFinMin = (int)substr($creneauFin, 0, 2) * 60 + (int)substr($creneauFin, 3, 2);
                                
                                if ($creneauDebutMin < $coursFinMin && $creneauFinMin > $coursDebutMin) {
                                    if ($coursDebutMin >= $creneauDebutMin && $coursDebutMin < $creneauFinMin) {
                                        $coursCell = $cours->LIBELMAT . "\n" . 
                                                    ($cours->NOM ? $cours->NOM . ' ' . $cours->PRENOM : '') . "\n" .
                                                    ($cours->CODESALLE ?? '');
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                    $row[] = $coursCell ?: '-';
                }
                
                $data[] = $row;
            }

            // Créer le fichier CSV
            $filename = 'Emploi_du_temps_' . $classe . '_' . date('Y-m-d') . '.csv';
            
            $handle = fopen('php://temp', 'r+');
            
            // Ajouter le BOM UTF-8 pour Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($data as $row) {
                fputcsv($handle, $row, ';');
            }
            
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export Excel: ' . $e->getMessage());
            return redirect()->route('emploidutempsgeneral')->with('error', 'Erreur lors de l\'export Excel.');
        }
    }
}
