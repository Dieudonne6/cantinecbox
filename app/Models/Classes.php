<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Typeclasse;
use App\Models\Serie;
use App\Models\Promo;
use App\Models\Typeenseigne;


class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';
    public $timestamps = false;
    protected $primaryKey = 'CODECLAS';
    public $incrementing = false;
    protected $keyType = 'string';

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'CODEPROMO', 'CODEPROMO');
    }
     
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'CODECLAS', 'CODECLAS');
    }
}