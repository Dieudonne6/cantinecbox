<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'users';
    public $timestamps = false;

    // ✅ Colonnes autorisées en mass assignment
    protected $fillable = [
        'nomgroupe',
        'login',
        'nomuser',
        'prenomuser',
        'administrateur',
        'motdepasse',
        'user_actif',
        'date_desactivation',
        'date_change_mp',
        'frequence_mp',
        'saisir_mp',
    ];
}

