<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
class ConnexionDBController extends Controller
{
    
    public function connexion(Request $request){
        $nameserveur = $request->input('nameserveur');
        $namebase = $request->input('namebase');
        $user = $request->input('user');
        $password = $request->input('password');
        $envContent = "";
        $envContent = File::get(base_path('.env'));

        $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE=$namebase", $envContent);

        $envContent = preg_replace('/^DB_USERNAME=.*$/m', "DB_USERNAME=$user", $envContent);

        $envContent = preg_replace('/^DB_PASSWORD=.*$/m', "DB_PASSWORD=$password", $envContent);

        File::put(base_path('.env'), $envContent);
            return back()->with('status','Enregistrer avec succes');
    }

    public function logout(Request $request)
    {
       
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
