<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;
    protected $table = 'scolarit';
    public $timestamps = false;
    protected $fillable = [
        'NUMERO',
        'MATRICULE ',
        'DATEOP',
        'MONTANT',
        'AUTREF',
        'EDITE',
        'NUMRECU',
        'SIGNATURE',
        'FILLER_E',
        'FILLER_T',
        'MODEPAIE',
        'VALIDE',
        'MODIFIABLE',
        'DATESAISIE',
        'ANSCOL',
        'VERROUILLE',
        'MOTIF_SUPP',
        'IdSCOLARITE',
        'guid',
        'guid_matri',
        'SiTE',
    ];

    
    // Relation avec le modèle Eleve
    public function eleve() {
       return $this->belongsTo(Eleve::class, 'MATRICULE', 'MATRICULE');
    }
}