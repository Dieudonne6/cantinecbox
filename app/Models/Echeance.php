<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeance extends Model
{
    use HasFactory;
    protected $table = 'echeance';
    public $timestamps = false;
<<<<<<< HEAD

}
=======
    protected $fillable = ['NUMERO','DATEOP','APAYER','MATRICULE','ARRIERE','guid	','guid_matri','SiTE','idecheance', 'anneeacademique'];

}
>>>>>>> 19934c910b15826f016b9764909474e80f58d9ec
