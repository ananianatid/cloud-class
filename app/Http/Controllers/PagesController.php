<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Semestre;
use App\Models\EmploiDuTemps;
use App\Models\Cours;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class PagesController extends Controller
{
    public function displayDashboard() {
        $user = Auth::user();

        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        $etudiant = $user->etudiant;
        if (!$etudiant) {
            $cours = collect();
            return view('dashboard', compact('cours'))
                ->with('error', "Votre compte n'est pas associé à un profil étudiant.");
        }

        if (is_null($etudiant->promotion_id)) {
            $cours = collect();
            return view('dashboard', compact('cours'))
                ->with('error', "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration.");
        }

        $promotionId = $etudiant->promotion_id;
        $today = Carbon::today();

        // Find the semester whose start date is in the past and closest to today
        $closestSemestre = Semestre::where('promotion_id', $promotionId)
            ->whereDate('date_debut', '<=', $today)
            ->orderBy('date_debut', 'desc')
            ->first();

        if (!$closestSemestre) {
            $cours = collect();
            return view('dashboard', compact('cours'))
                ->with('error', "Aucun semestre trouvé pour votre promotion.");
        }

        $cours = $closestSemestre->matieres;

        return view('dashboard', compact('cours', 'closestSemestre'));
    }

    public function displayEmploisDuTempsActif() {
        $user = Auth::user();

        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        $etudiant = $user->etudiant;
        if (!$etudiant) {
            $emploisDuTemps = collect();
            return view('pages.timeTable', compact('emploisDuTemps'))
                ->with('error', "Votre compte n'est pas associé à un profil étudiant.");
        }

        if (is_null($etudiant->promotion_id)) {
            $emploisDuTemps = collect();
            return view('pages.timeTable', compact('emploisDuTemps'))
                ->with('error', "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration.");
        }

        $promotionId = $etudiant->promotion_id;

        $emploisDuTemps = EmploiDuTemps::with(['semestre.promotion'])
            ->whereHas('semestre', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })
            ->orderByDesc('actif')
            ->orderBy('categorie')
            ->get();

        return view('pages.temps.emploi-du-temps-actif', compact('emploisDuTemps'));
    }

    public function displaySemestres() {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un étudiant
        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        // Récupérer l'étudiant lié et gérer les cas d'absence de promotion
        $etudiant = $user->etudiant;
        if (!$etudiant) {
            $semestres = collect();
            return view('dashboard', compact('semestres'))
                ->with('error', "Votre compte n'est pas associé à un profil étudiant.");
        }

        if (is_null($etudiant->promotion_id)) {
            $semestres = collect();
            return view('dashboard', compact('semestres'))
                ->with('error', "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration.");
        }

        // Récupérer les semestres de cette promotion
        $semestres = Semestre::where('promotion_id', $etudiant->promotion_id)->get();

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

    public function displayEmploisDuTemps() {
        $user = Auth::user();

        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        $etudiant = $user->etudiant;
        if (!$etudiant) {
            $emploisDuTemps = collect();
            return view('pages.timeTable', compact('emploisDuTemps'))
                ->with('error', "Votre compte n'est pas associé à un profil étudiant.");
        }

        if (is_null($etudiant->promotion_id)) {
            $emploisDuTemps = collect();
            return view('pages.timeTable', compact('emploisDuTemps'))
                ->with('error', "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration.");
        }

        $promotionId = $etudiant->promotion_id;

        $emploisDuTemps = EmploiDuTemps::with(['semestre.promotion'])
            ->whereHas('semestre', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })
            ->orderByDesc('actif')
            ->orderBy('categorie')
            ->get();

        return view('pages.timeTable', compact('emploisDuTemps'));
    }
}
