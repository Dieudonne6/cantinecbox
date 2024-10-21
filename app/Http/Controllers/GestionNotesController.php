<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Users;
class GestionNotesController extends Controller
{
    public function repartitionclassesparoperateur() {
        $users = Users::all();
        return view('pages.gestionnotes.repartitionclassesparoperateur', compact('users'));
    }
}
