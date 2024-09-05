<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $primaryKey = 'CODECLAS';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    public function serie()
    {
        return $this->belongsTo(Serie::class, 'SERIE');
    }
}
