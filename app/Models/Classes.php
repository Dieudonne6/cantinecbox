<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Typeclasse;
use App\Models\Serie;
use App\Models\Promo;
use App\Models\Typeenseigne;
use App\Models\Matiere;
use App\Models\Agent;
use App\Models\Cours;


class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';
    public $timestamps = false;
    protected $primaryKey = 'CODECLAS';
    public $incrementing = false;
    protected $keyType = 'string';

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'CODEPROMO', 'CODEPROMO');
    }
     
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'CODECLAS', 'CODECLAS');
    }
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'CODECLAS', 'CODECLAS');
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'clasmat', 'CODECLAS', 'CODEMAT');
    }

    public function agent()
    {
        // Relation many-to-many avec la table pivot 'agent_classe'
        return $this->hasMany(Agent::class, 'agent', 'CODECLAS', 'MATRICULE');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class, 'CODECLAS', 'CODECLAS');
    }
}