<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $table = 'classes';

    public function serie()
    {
        return $this->belongsTo(Serie::class, 'SERIE'); // Assurez-vous que le nom de la clé étrangère est correct
    }
}
