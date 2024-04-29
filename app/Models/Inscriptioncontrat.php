<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscriptioncontrat extends Model
{
    use HasFactory;
    protected $table = 'inscriptioncontrat';
    public $timestamps = false;


    protected $fillable = ['id_contrat', 'id_moiscontrat', 'anne_inscrption'];

}
