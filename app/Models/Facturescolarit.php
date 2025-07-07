<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturescolarit extends Model
{
    use HasFactory;
    protected $table = 'facturescolarit';
    //protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'uid',
        'id' ,
        'codemecef' ,
        'codemeceffacoriginale' ,
        'counters',
        'nim',
        'dateHeure' ,
        'ifuEcole' ,
        'MATRICULE' ,
        'nom' ,
        'classe' ,
        'itemfacture' ,
        'montant_total' ,
        'tax_group' ,
        'date_time' ,
        'qrcode' ,
        'statut',
        'NUMRECU',
        'mode_paiement' 
];

}