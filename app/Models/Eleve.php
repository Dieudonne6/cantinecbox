<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrat;

class Eleve extends Model
{
    use HasFactory;
    protected $table = 'eleve';
    public function contrats()
    {
        return $this->hasMany(Contrat::class,'eleve_contrat', 'MATRICULE');
    }
}
