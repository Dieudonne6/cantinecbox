<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;
    protected $table = 'series';
    public $timestamps = false;
    // protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'SERIE',
        'LIBELSERIE',
        'CYCLE',
        // Autres colonnes nécessaires
    ];

}
