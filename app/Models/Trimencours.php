<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimencours extends Model
{
    use HasFactory;
    protected $connection = 'mysql'; // Nom de la connexion définie dans config/database.php
    protected $table = 'trimencours';
    protected $primaryKey = 'idTrimencours';
    protected $fillable = [
        'timestreencours',
        'TYPEAN',
        'CodeSITE'
    ];
}
