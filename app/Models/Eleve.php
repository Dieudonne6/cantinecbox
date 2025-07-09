<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrat;
use App\Models\eleves;
use App\Models\Notes;

class Eleve extends Model
{
    use HasFactory;

    protected $casts = [
        'MATRICULEX' => 'string',
    ];
    protected $table = 'eleve';
    protected $primaryKey = 'MATRICULE';
    public $timestamps = false;
    protected $fillable = [
        'MATRICULE',
        'MATRICULEX', // Ajouté
        'NOM',
        'PRENOM',
        'CODECLAS',
        'SEXE',
        'Reduction',
        'DATENAIS',
        'LIEUNAIS',
        'PHOTO',
        'CodeReduction',
        'EXONERER',
        'numordre',
        'SERIE',
        'ARRIERE',
        'ARRIERE_INITIAL',
        'STATUT', // Ajouté
    ];
    
    
    

    // Définir la relation avec les notes
    public function notes()
    {
        return $this->hasMany(Notes::class, 'MATRICULE', 'MATRICULE');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'eleve_contrat', 'MATRICULE');
    }
    public function classe()
    {
        return $this->belongsTo(Classes::class, 'CODECLAS', 'CODECLAS');
    }

    // Relation avec le modèle Faute
    public function fautes() {
        return $this->hasMany(Faute::class, 'MATRICULE', 'MATRICULE');
    }
    public function reduction() {
        return $this->belongsTo(Reduction::class, 'CodeReduction', 'CodeReduction'); // Assurez-vous que les clés sont correctes
    }
    
        // Relation avec le modèle Scolarite
        public function Scolarite() {
            return $this->belongsTo(Scolarite::class, 'MATRICULE', 'MATRICULE');
         }
     
         // Relation avec le modèle Echeance
         public function Echeance()
         {
             return $this->hasMany(Echeance::class, 'MATRICULE', 'MATRICULE');
         }
         public function promotion() {
            return $this->belongsTo(Promo::class, 'CODEPROMO');
        }
        public function serie() {
            return $this->belongsTo(Serie::class, 'SERIE');
        }
}