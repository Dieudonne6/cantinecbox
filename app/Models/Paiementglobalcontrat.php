<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiementglobalcontrat extends Model
{
    use HasFactory;
    protected $table = 'paiementglobalcontrat';
    public $timestamps = false;

    protected $primaryKey = 'id_paiementcontrat'; // Clé primaire de votre table


}