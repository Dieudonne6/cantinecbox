<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;
    protected $table = 'notes';
    public $timestamps = false;
    protected $fillable = ['MI','DEV1','DEV2','DEV3','MS','MATRICULE','TEST','CODEMAT'];

    // Définir la relation inverse
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'MATRICULE', 'MATRICULE');
    }
}
