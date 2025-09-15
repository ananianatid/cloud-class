<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Semestre;
use Illuminate\Support\Facades\Auth;
class PagesController extends Controller
{
    public function displaySemestres() {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un étudiant
        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        // Récupérer la promotion de l'étudiant connecté
        $promotionId = $user->etudiant->promotion_id;

        // Récupérer les semestres de cette promotion
        $semestres = Semestre::where('promotion_id', $promotionId)->get();

        return view('dashboard', compact('semestres'));
    }

    public function displayMatieres(Semestre $semestre) {
        $matieres = $semestre->matieres;
        return view('pages.subjects',['semestre'=>$semestre,'matieres'=>$matieres]);

    }

    public function displayFichiers(Semestre $semestre, Matiere $matiere){
        if ($matiere->semestre_id !== $semestre->id) {
            abort(404);
        }

        $fichiers = $matiere->fichiers ?? collect();
        return view('pages.files', [
            'matiere' => $matiere,
            'fichiers' => $fichiers,
        ]);
    }
}
