<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Matieres;
use App\Models\Notes;
use Illuminate\Http\Request;

class ReleveparelevesController extends Controller
{
    public function relevespareleves(Request $request)
    {
        // Récupération de toutes les classes pour le menu déroulant
        $classes = Classe::all();
        
        // Par défaut, les tableaux restent vides
        $notes = collect([]);
        $matieres = collect([]);
        
        // Vérifier l'action envoyée par le formulaire
        if ($request->input('action') === 'calculer') {
            $classeCode = $request->input('classe', null);
            $typeImpression = $request->input('type_impression', 'classe');
            // Les colonnes sont envoyées sous la forme d'un tableau associatif 
            // [ 'CODEMAT' => [ 'INT1', 'INT2', ... ] ]
            $colonnesSelectionnees = $request->input('colonnes', []);
            
            // Si une classe est sélectionnée
            if (!empty($classeCode)) {
                // Récupération des notes pour la classe choisie
                $notesQuery = Notes::query()->where('CODECLAS', $classeCode);
                
                // Si on souhaite filtrer sur un élève précis
                if ($typeImpression === 'eleve') {
                    $matricule = $request->input('matricule', null);
                    if (!empty($matricule)) {
                        $notesQuery->where('MATRICULE', $matricule);
                    }
                }
                
                $notes = $notesQuery->get();
                
                // Pour chaque note, calcul de la moyenne basée sur les colonnes sélectionnées pour la matière
                foreach ($notes as $note) {
                    $subjectCode = $note->CODEMAT;
                    $selectedColumns = $colonnesSelectionnees[$subjectCode] ?? [];
                    
                    $sum = 0;
                    $count = 0;
                    foreach ($selectedColumns as $column) {
                        // Vérifier si la colonne est présente et non nulle
                        if (isset($note->$column) && !is_null($note->$column)) {
                            $sum += $note->$column;
                            $count++;
                        }
                    }
                    // Calcul de la moyenne pour cette note, si au moins une colonne a été sélectionnée
                    $average = $count > 0 ? $sum / $count : null;
                    // Affecter la moyenne dans une propriété (ici, nous utilisons "moyenne" en tant qu'attribut virtuel)
                    $note->moyenne = $average;
                    
                    // Optionnel : mise à jour de la note dans la base de données si vous souhaitez persister la moyenne
                    // $note->update(['MS1' => $average]);
                }
                
                // Tri des notes par moyenne décroissante pour établir l'ordre de mérite
                $sortedNotes = $notes->sortByDesc('moyenne')->values();
                $rank = 1;
                foreach ($sortedNotes as $note) {
                    $note->RANG = $rank++;
                    // Optionnel : persister le rang dans la base de données
                    // $note->update(['RANG' => $note->RANG]);
                }
                $notes = $sortedNotes;
                
                // Facultatif : récupérer les matières de la classe pour éventuellement les afficher dans la vue
                $matieres = Matieres::where('CODECLAS', $classeCode)->get();
            }
        }
        
        // Retour de la vue avec les variables nécessaires
        return view('pages.notes.relevespareleves', [
            'classes'   => $classes,
            'notes'     => $notes,
            'matieres'  => $matieres,
            // Vous pouvez transmettre d'autres paramètres au besoin (par ex. pour conserver les choix de l'utilisateur)
        ]);
    }
}