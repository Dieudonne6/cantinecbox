<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $table = 'profils';
    protected $primaryKey = 'Numeroprofil';
    public $incrementing = true; 
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'NomProfil',
        'SalaireBase',
        'TauxCycle1jour',
        'TauxCycle2jour',
        'TauxHeureSupC1',
        'TauxCycle1Soir',
        'TauxCycle2Soir',
        'TypeImpot',
        'TauxUniqueJour',
        'TauxUniqueSoir',
        'CalculerCnss',
        'TauxHeureSupC2',
        'TauxHeureSupUnique',
        'NbHeuresDu',
    ];
}
