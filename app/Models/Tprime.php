<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tprime extends Model
{
    use HasFactory;

    protected $table = 'tprimes';
    protected $primaryKey = 'CODEPR';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CODEPR',
        'LIBELPR',
        'TYPEPR',
        'MONTANTFIXE',
        'MONTANTVAR',
        'BASEVARIABLE',
    ];
}
