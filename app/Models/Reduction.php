<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reduction extends Model
{
    use HasFactory;
    protected $table = 'reduction';
    public $timestamps = false;
    protected $primaryKey = 'CodeReduction'; // Clé primaire 
    protected $fillable = [
        'Codereduction',
        'LibelleReduction',
        'Reduction_scolarite',
        'Reduction_arriere',
        'Reduction_frais1',
        'Reduction_frais2',
        'Reduction_frais3',
        'Reduction_frais4',
        'mode'
    ];

}
