<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'absence';
    public $timestamps = false; // Désactive les timestamps si non utilisés

    protected $fillable = [
        'IDABSENCE',
        'MATRICULE',
        'DATEOP',
        'CODEMAT',
        'SEMESTRE',
        'MOTIF',
        'ABSENT',
        'RETARD',
        'HEURES',
        'MOTIFVALALBLE',
        'guid',
        'guid_matri',
        'SiTE',
        'guid_mat',
        'anneeacademique',
        'PERIODE',
    ];
}