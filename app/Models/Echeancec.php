<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeancec extends Model
{
    use HasFactory;
    protected $table = 'echeancc';
    public $timestamps = false;
    protected $fillable = ['NUMERO','DATEOP','APAYER','CODECLAS','APAYER2','DATEOP2	','FRACTION1','FRACTION2'];
    // protected $primaryKey = 'IDFAUTES';
}
