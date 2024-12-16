<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facturescolarit extends Model
{
    use HasFactory;
    protected $table = 'facturescolarit';
    protected $primaryKey = 'id';

    protected $fillable = [
    'ifu_ecole',
    'client_name',
    'client_matricule',
    'client_ifu',
    'items',
    'total_price',
    'tax_group',
    'counters',
    'nim',
    'date_time',
    'qr_code',
    'qr_code_image_path',
    'confirmation_code'];

}