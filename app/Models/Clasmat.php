<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Concerns\HasCompositeKey;

class Clasmat extends Model
{
    use HasFactory, HasCompositeKey;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'clasmat';
    protected $primaryKey = ['CODECLAS', 'CODEMAT'];
    protected $keyType = 'string';

    protected $fillable = ['COEF', 'MOYFO', 'MOYFA', 'MOYFO1', 'MOYFO2', 'MOYFO3', 'MOYFO4', 'MOYFA1', 'MOYFA2', 'MOYFA3', 'MOYFA4', 'CODECLAS', 'CODEMAT', 'MODIFIER', 'FONDAMENTALE', 'ANSCOL', 'NBHEURE', 'TRANCHES'];

    public function matiere()
    {
        // Ajustez le namespace et la clé si besoin
        return $this->belongsTo(\App\Models\Matieres::class, 'CODEMAT', 'CODEMAT');
    }
}