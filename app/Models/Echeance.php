<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeance extends Model
{
    use HasFactory;
    protected $table = 'echeance';
    public $timestamps = false;
    protected $fillable = ['NUMERO','DATEOP','APAYER','MATRICULE','ARRIERE','guid	','guid_matri','SiTE','idecheance', 'anneeacademique'];

}