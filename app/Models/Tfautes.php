<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tfautes extends Model
{
    use HasFactory;
    protected $connection = 'mysql2'; // Nom de la connexion définie dans config/database.php
    protected $table = 'tfautes'; // Nom de la table si ce n'est pas le pluriel du modèle
    protected $primaryKey = 'idTFautes'; // Nom de la colonne clé primaire
    protected $fillable = [
        'idTFautes',
        'LibelFaute', 
        'Sanction_Indicative', 
        'Sanction_en_heure', 
        'Sanction_en_points'
    ];
    public $timestamps = false; // Désactive les timestamps si non utilisés
}