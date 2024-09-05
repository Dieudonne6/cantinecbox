<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrat;
use App\Models\Notes;

class Eleve extends Model
{
    use HasFactory;
    protected $table = 'eleve';
    protected $primaryKey = 'MATRICULE';
    public $timestamps = false;
    protected $fillable = ['MATRICULE','NOM','PRENOM','CODECLAS','SEXE','Reduction','DATENAIS','LIEUNAIS'];
    
    

    // Définir la relation avec les notes
    public function notes()
    {
        return $this->hasMany(Notes::class, 'MATRICULE', 'MATRICULE');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'eleve_contrat', 'MATRICULE');
    }

    // Relation avec le modèle Faute
    public function fautes() {
        return $this->hasMany(Faute::class, 'MATRICULE', 'MATRICULE');
    }

}