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

    public function emploidutempsautomatique()
    {
        return view('pages.GestionPersonnel.Emploidutempsautomatique');
    }

    public function configquotahoraires()
    {
        return view('pages.GestionPersonnel.configquotahoraires');
    }
}
