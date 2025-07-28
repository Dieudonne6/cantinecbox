<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

     protected $table = 'rapport';

    public $timestamps = false;

    protected $primaryKey = 'MATRICULEID';

    protected $fillable = [
        'RANG',
        'MATRICULEID',
        'MATRICULEX',
        'NOM',        
        'PRENOM', 
        'MOY1',
        'MOY2',                                                                         
        'MOY3',
        'MOYAN',
        'OBSERVATION',
        'STATUTF',
        'CODECLAS',
        'SEXE',
        'STATUT',
        'ANSCOL',
    ];

}
