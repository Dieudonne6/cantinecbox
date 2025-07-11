<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionConfigAnnuel extends Model
{
    use HasFactory;

    protected $table = 'decision_config_annuel';

    public $timestamps = false;

 

    protected $fillable = [
        'seuil_Passage',
        'Min_Cycle1',
        'Min_Cycle2',
        'Seuil_Felicitations',
        'Seuil_Encouragements',
        'Seuil_tableau_Honneur',
        'Promotion',
        'Statut',
        'StatutF',
        'Cycle',
    ];

}
