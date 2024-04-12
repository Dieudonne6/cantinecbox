<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function inscription(){
        return view('pages.inscription');
    }

    public function paiement(){
        return view('pages.paiement');
    } 

    public function nouveaucontrat(){
        return view('pages.nouveaucontrat');
    }

  

    public function connexiondonnées(){
        return view('pages.connexiondonnées');
    }
    
    public function frais(){
        return view('pages.frais');
    }

}
