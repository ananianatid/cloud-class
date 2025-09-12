<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Semestre ;
class PagesController extends Controller
{
    public function displaySemesters() {
        $semestres = Semestre::where('promotion_id', 1)->get();
        // Créer un nouveau semestre

        return view('dashboard');
    }
}
