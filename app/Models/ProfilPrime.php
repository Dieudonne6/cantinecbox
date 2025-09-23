<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPrime extends Model
{
    use HasFactory;

    protected $table = 'profils_primes';
    protected $primaryKey = 'Numeroprofil';
    public $incrementing = true; 
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'CODEPR',
        'MONTANTFIXE',
        'MONTANTVAR',
        'BASEVARIABLE',
        'ComptePrime',
        'ChapitrePrime',
    ];
}
