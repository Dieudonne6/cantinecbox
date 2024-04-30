<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiementcontrat extends Model
{
    use HasFactory;
    protected $table = 'paiementcontrat';
    public $timestamps = false;

    protected $primaryKey = 'id_paiementcontrat'; // Clé primaire de votre table

    protected $fillable = ['soldeavant_paiementcontrat', 'montant_paiementcontrat', 'soldeapres_paiementcontrat', 'id_contrat', 'date_paiementcontrat', 'mois_paiementcontrat', 'anne_paiementcontrat', 'reference_paiementcontrat', 'statut_paiementcontrat', 'id_paiementglobalcontrat'];

}         
                                                                                                       
