<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAgent extends Model
{
    use HasFactory;

    protected $table = 'typeagent';
    public $timestamps = false; 
    protected $primaryKey = null;  
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'LibelTypeAgent',
        'Quota',
    ];
}
