<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametreController extends Controller
{
    // public function index()
    // {
    //     return view('parametre.index');
    // }

    // public function cantine()
    // {
    //     return view('parametre.cantine');
    // }

    public function inscriptionsDiscipline()
    {
        return view('parametre.inscriptions');
    }

    public function tables()
    {
        return view('pages.parametre.tables');
    }

    public function bornesExercice()
    {
        return view('pages.parametre.bornes');
    }

    public function opOuverture()
    {
        return view('pages.parametre.op_ouverture');
    }

    public function configImprimante()
    {
        return view('pages.parametre.config_imprimante');
    }

    public function changementTrimestre()
    {
        return view('pages.parametre.changement_trimestre');
    }
}
