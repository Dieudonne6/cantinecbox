<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BulletinController extends Controller
{
    public function bulletindenotes()
    {
        return view('pages.notes.bulletindenotes');
    }
}
