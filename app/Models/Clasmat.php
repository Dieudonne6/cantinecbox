<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clasmat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'clasmat';
    // protected $primaryKey = ['CODECLAS'];
    // protected $keyType = 'string';
    protected $fillable = ['id', 'COEF', 'MOYFO', 'MOYFA', 'MOYFO1', 'MOYFO2', 'MOYFO3', 'MOYFO4', 'MOYFA1', 'MOYFA2', 'MOYFA3', 'MOYFA4', 'CODECLAS', 'CODEMAT', 'MODIFIER', 'FONDAMENTALE', 'ANSCOL', 'NBHEURE', 'TRANCHES'];

    public function matiere()
    {
        // Ajustez le namespace et la clé si besoin
        return $this->belongsTo(\App\Models\Matieres::class, 'CODEMAT', 'CODEMAT');
    }
}