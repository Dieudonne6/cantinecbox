<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $primaryKey = 'IDNOTES';
    // public $timestamps = false;

    // Active la gestion automatique des timestamps
    public $timestamps = true;

    // Redéfinition des colonnes created_at / updated_at
    const CREATED_AT = 'DATECREE';
    const UPDATED_AT = 'DATEMODIF';

    // (Optionnel) Si vous voulez un format précis
    protected $dateFormat = 'Y-m-d H:i:s';

    
    protected $fillable = [
        'INT1', 'INT2', 'INT3', 'INT4', 'MI', 'DEV1', 'DEV2', 'DEV3',
        'MS', 'COEF', 'SEMESTRE', 'MATRICULE', 'CODECLAS',
        'CODEMAT', 'CODEUSER', 'RANG', 'FILLER_T', 'FILLER_E', 'TEST',
        'MS1', 'MODIFIER', 'VERROUILLE', 'DATECREE', 'DATEMODIF', 'ANSCOL',
        'INT5', 'INT6', 'INT7', 'INT8', 'INT9', 'INT10', 'SystemeNotes','IDNOTES', 'SEMMATMATR'
    ];

    // Définir la relation inverse
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'MATRICULE', 'MATRICULE');
    }

    public function Classes()
    {
        return $this->belongsTo(Classes::class, 'CODECLAS', 'CODECLAS');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'CODEMAT', 'CODEMAT');
    }

     
}