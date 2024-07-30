<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Serie;
use Illuminate\Http\Request;

class GestionclasseController extends Controller
{
      public function groupes(){
        
        return view('pages.inscriptions.groupes');
    }

    public function series(Request $request){

      $series = DB::table('series')->select('SERIE', 'LIBELSERIE')->get();

      return view ('pages.inscriptions.series')->with('series', $series);
  }
}
