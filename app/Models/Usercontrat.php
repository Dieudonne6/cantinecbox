<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usercontrat extends Model
{
    use HasFactory;

    protected $table = 'usercontrat';
    protected $primaryKey = 'id_usercontrat';
    public $timestamps = false;

    // Colonnes qu’on peut remplir avec create() ou update()
    protected $fillable = [
        'nom_usercontrat',
        'prenom_usercontrat',
        'login_usercontrat',
        'password_usercontrat',
        'statut_usercontrat',
    ];
}
