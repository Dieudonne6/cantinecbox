<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $table = 'journal';
    public $timestamps = false;
    protected $fillable = [
        'NUMJ', 
		'NCOMPTE', 
		'DATEOP', 
		'DEBIT', 
		'CREDIT', 
		'REF', 
		'ORDRERECET',	
		'LIBELOP',	
		'ANSCOL',	
		'OBSER',	
		'PAYE', 
		'MODIFIABLE', 
		'NCOMPTECOURT', 
		'CHAPITRE', 
		'TYPECPTE',	
		'SIGNATURE',	
		'FILLER_T', 
		'NumFRais',	
		'CHAPITRE2',	
		'NCOMPTE2', 
		'NUMDEPREC',	
		'MONTANTGLOBAL',	
		'POURCENTAGE',	
		'NUMJORIGINE',	
		'SOURCE',	
		'INFOSOURCE', 
		'MODEPAIE', 
		'NUMRECU', 
		'EDITE',	
		'VALIDE',	
		'GENERE', 
		'SUPER_SEUL_DEVERROUILLE',
    ];

	public function chapitre() {
        return $this->belongsTo(Chapitre::class, 'CHAPITRE', 'CHAPITRE'); // Assurez-vous que 'chapitre' correspond à la clé étrangère dans la table Journal
    }
}