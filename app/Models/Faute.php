<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faute extends Model
{
    use HasFactory;
    
    // Assurez-vous que la table existe et que les colonnes correspondent
    protected $table = 'fautes';
    protected $primaryKey = 'IDFAUTES';

    // Définir les attributs que vous souhaitez récupérer
    protected $fillable = ['IDFAUTES', 'MATRICULE', 'DATEOP', 'FAUTE', 'SANCTION', 'NBHEURE', 'SEMESTRE', 'COLLECTIVE',
                            'idTFautes', 'IDABSENCE', 'guid', 'guid_matri', 'SiTE','guid_abs', 'anneeacademique'];
    public $timestamps = false; // Disable timestamps if not needed

    // Relation avec le modèle Eleve
    public function eleve() {
        return $this->belongsTo(Eleve::class, 'MATRICULE', 'MATRICULE');
    }
    
    public function typeFaute() {
        return $this->belongsTo(TFautes::class, 'idTFautes', 'idTFautes');
    }
}