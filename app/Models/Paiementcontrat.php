<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrat;
use App\Models\Moiscontrat;
class Paiementcontrat extends Model
{
    use HasFactory;
    protected $table = 'paiementcontrat';
    public $timestamps = false;

    protected $primaryKey = 'id_paiementcontrat'; // Clé primaire de votre table

    protected $fillable = ['soldeavant_paiementcontrat', 'montant_paiementcontrat', 'soldeapres_paiementcontrat', 'id_contrat', 'date_paiementcontrat', 'mois_paiementcontrat', 'anne_paiementcontrat', 'reference_paiementcontrat', 'statut_paiementcontrat', 'id_paiementglobalcontrat'];
    public function contrat() {
        return $this->belongsTo(Contrat::class, 'id_contrat', 'id_contrat');
    }
    public function moisContrat()
    {
        return $this->belongsTo(Moiscontrat::class, 'mois_paiementcontrat', 'id_moiscontrat');
    }
}         
                                                                                                       
