<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agent';

    public $timestamps = false;

    protected $fillable = [
        'NOM ',
        'PRENOM',
        'DATENAIS',
        'LIEUNAIS',
        'NATION',
        'SEXE',
        'SITMAT',
        'PROVENANCE',
        'CODEDEPT',
        'POSITMILIT',
        'NBENF',
        'DIPLOMEAC',
        'DIPLOMEPRO',
        'DATEDEP',
        'DATEENT',
        'DATEENTADM',
        'DATEFONC',
        'DATENOM',
        'REFNOM',
        'DATETITU',
        'REFTITU',
        'GRADE',
        'INDICER',
        'INDICEP',
        'NOTE',
        'POSTE',
        'FONCTIONP',
        'ADRPERS',
        'ADRVAC',
        'CONJOINT',
        'LIEUCONJOINT',
        'PREVENIR',
        'RAISONINT',
        'DATEDEBINT',
        'ADRPREV',
        'DATEFININT',
        'CODECLAS',
        'SIGNEP',
        'TYPEAGENT',
        'DATERET',
        'TAUXHORAIRE',
        'SELET',
        'CYCLES',
        'FONCTIONS',
        'CODECLAS',
        'SIGNEP',
        'SALBASE',
        'TAUXHS',
        'NBHEURE',
        'PHOTO',
        'PriseEnchargeEtat',
        'CHAPITRE',
        'TAUXHORAIRE2',
        'NUMCNSS',
        'Numeroprofil',
        'LibelTypeAgent',
        'NbHeuredu',
        'Enseignant',
        'CBanque',
        'NCompteBanque',
        'IFU',
        'CompteAvance',
        'ChapitreAvance',
        'TelAgent',
        'guid',
        'SiTE',
        'anneeacademique',
    ];
}
