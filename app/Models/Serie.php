<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;
    protected $table = 'series';
    public $timestamps = false;
    // protected $primaryKey = 'SERIE';

    public function classes()
    {
        return $this->hasMany(Classe::class, 'SERIE'); // Assurez-vous que le nom de la clé étrangère est correct
    }

}
