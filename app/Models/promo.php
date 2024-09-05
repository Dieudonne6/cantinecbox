<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'promo';
    protected $primaryKey = 'CODEPROMO';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CODEPROMO',
        'LIBELPROMO',
        'Niveau',
        'TYPEENSEIG'
    ];
    
}
