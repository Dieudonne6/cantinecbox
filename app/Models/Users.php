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
        'ADMINISTRATEUR',
        'motdepasse',
        'user_actif',
        'date_desactivation',
        'date_change_mp',
        'frequence_mp',
        'saisir_mp',
    ];

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function isActive()
    {
        return $this->user_actif == 1;
    }

    /**
     * Obtenir le groupe de l'utilisateur
     */
    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'nomgroupe', 'nomgroupe');
    }

    /**
     * Obtenir le nom complet de l'utilisateur
     */
    public function getFullNameAttribute()
    {
        return $this->prenomuser . ' ' . $this->nomuser;
    }

}

