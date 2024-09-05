<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;
    protected $table = 'absence'; // Nom de la table si ce n'est pas le pluriel du modèle
    public $timestamps = false; // Désactive les timestamps si non utilisés

}