<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrat;

class Eleve extends Model
{
    use HasFactory;
    protected $table = 'eleve';
    public $timestamps = false;
    protected $fillable = ['MATRICULE','NOM','PRENOM','CODECLAS','SEXE','Reduction','DATENAIS','LIEUNAIS'];



    // Définir la relation avec les notes
    public function notes()
    {
        return $this->hasMany(Notes::class, 'MATRICULE', 'MATRICULE');
    }

}
