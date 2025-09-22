<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class GestionPersonnelController extends Controller
{
    //Création de profil pour personnel
    public function UpdatePersonnel(Request $request){  
        return view('pages.GestionPersonnel.UpdatePersonnel');
    }

    //Ajout d'agent
     public function AddAgent(Request $request){  
        return view('pages.GestionPersonnel.addAgent');
    }
}

