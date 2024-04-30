<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;
    protected $table = 'contrat';

    protected $primaryKey = 'id_contrat'; // Clé primaire de votre table

    public $timestamps = false;
}
