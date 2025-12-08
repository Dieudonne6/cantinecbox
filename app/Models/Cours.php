<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $table = 'cours';
    protected $primaryKey = 'idcours';
    public $timestamps = false;

    protected $fillable = [
        'JOUR',
        'HEURE',
        'CODECLAS',
        'CODEMAT',
        'MATRICULE',
        'CODESALLE',
        'libre',
        'Typeenreg',
        'guid',
        'guid_classe',
        'SiTE',
        'guid_mat',
        'guid_agent',
        'anneeacademique'
    ];

    protected $casts = [
        'JOUR' => 'integer',
        'CODEMAT' => 'integer',
        'MATRICULE' => 'integer',
        'libre' => 'integer',
        'idcours' => 'integer'
    ];

    // Relations
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'CODECLAS', 'CODECLAS');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'CODEMAT', 'CODEMAT');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'MATRICULE', 'MATRICULE');
    }

    public function salle()
    {
        return $this->belongsTo(Salles::class, 'CODESALLE', 'CODESALLE');
    }

    // Méthodes utilitaires
    public static function getJourNumber($jourNom)
    {
        $jours = [
            'Lundi' => 1,
            'Mardi' => 2,
            'Mercredi' => 3,
            'Jeudi' => 4,
            'Vendredi' => 5,
            'Samedi' => 6
        ];

        return $jours[$jourNom] ?? 1;
    }

    public static function formatHeure($heureDebut, $heureFin)
    {
        // Format: "07:00-08:00" ou juste "07:00" selon les besoins
        return $heureDebut . '-' . $heureFin;
    }

    // Vérifier les conflits d'horaires
    public static function hasConflict($jour, $heure, $matricule = null, $codesalle = null, $codeclas = null, $excludeId = null)
    {
        $query = self::where('JOUR', $jour)->where('HEURE', $heure);

        if ($excludeId) {
            $query->where('idcours', '!=', $excludeId);
        }

        // Vérifier conflit professeur
        if ($matricule) {
            $profConflict = $query->clone()->where('MATRICULE', $matricule)->exists();
            if ($profConflict) return 'professeur';
        }

        // Vérifier conflit salle
        if ($codesalle) {
            $salleConflict = $query->clone()->where('CODESALLE', $codesalle)->exists();
            if ($salleConflict) return 'salle';
        }

        // Vérifier conflit classe
        if ($codeclas) {
            $classeConflict = $query->clone()->where('CODECLAS', $codeclas)->exists();
            if ($classeConflict) return 'classe';
        }

        return false;
    }
}
