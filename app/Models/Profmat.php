<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profmat extends Model
{
    use HasFactory;

    protected $table = 'profmat';
    public $timestamps = false;

    protected $fillable = [ 
        'CODEMAT',
        'MATRICULE',
    ];

}
