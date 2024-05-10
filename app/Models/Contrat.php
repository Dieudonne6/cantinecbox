<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Eleve;
use App\Models\Paiementcontrat;
class Contrat extends Model
{
    use HasFactory;
    protected $table = 'contrat';

    protected $primaryKey = 'id_contrat'; // Clé primaire de votre table

    public $timestamps = false;
    public function eleve() {
        return $this->belongsTo(Eleve::class, 'eleve_contrat', 'MATRICULE');
    }

    public function paiements() {
        return $this->hasMany(Paiementcontrat::class, 'id_contrat', 'id_contrat');
    }
}
