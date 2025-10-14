<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispos extends Model
{
    use HasFactory;

    protected $table = 'dispo';
    public $timestamps = false;

    protected $fillable = [
        'JOUR',
        'HEURE',
        'MATRICULE',
    ];

}
