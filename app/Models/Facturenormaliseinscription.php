<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturenormaliseinscription extends Model
{
    use HasFactory;
    protected $table = 'facturenormaliseinscription';

    protected $fillable = ['id', 'codemecef', 'counters', 'nim', 'dateHeure', 'ifuEcole' , 'MATRICULE', 'TOTALHT', 'TOTALTVA', 'classe' , 'nom', 'designation', 'datepaiementcontrat',  'qrcode' , 'statut' ,   'montant_total'];
    public $timestamps = false;


}
