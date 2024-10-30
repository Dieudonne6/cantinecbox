<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $primaryKey = 'IDNOTES';
    public $timestamps = false;
    protected $fillable = [
        'INT1', 'INT2', 'INT3', 'INT4', 'MI', 'DEV1', 'DEV2', 'DEV3',
        'MS Index', 'COEF', 'SEMESTRE Index', 'MATRICULE', 'CODECLAS',
        'CODEMAT', 'CODEUSER', 'RANG', 'FILLER_T', 'FILLER_E', 'TEST',
        'MS1', 'MODIFIER', 'VERROUILLE', 'DATECREE', 'DATEMODIF', 'ANSCOL',
        'INT5', 'INT6', 'INT7', 'INT8', 'INT9', 'INT10', 'SystemeNotes','IDNOTES'
    ];

    // Définir la relation inverse
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'MATRICULE', 'MATRICULE');
    }
}