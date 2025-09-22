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

        $semestres = Semestre::where('promotion_id', $etudiant->promotion_id)->get();

        return view('pages.semestres', compact('semestres'));
    }

    public function diplaySemestre(Semestre $semestre){
        $matieres = $semestre->matieres()
            ->with(['enseignant.user','unite'])
            ->get();

        return view('pages.semestre', [
            'semestre' => $semestre,
            'matieres' => $matieres,
        ]);
    }

    public function displayMatiere(Semestre $semestre, Matiere $matiere){
        // Log pour débogage
        \Log::info('displayMatiere appelé', [
            'semestre_id' => $semestre->id,
            'matiere_id' => $matiere->id,
            'matiere_semestre_id' => $matiere->semestre_id
        ]);

        // Vérifier que la matière appartient bien au semestre
        if ($matiere->semestre_id !== $semestre->id) {
            \Log::error('Matière n\'appartient pas au semestre', [
                'semestre_id' => $semestre->id,
                'matiere_id' => $matiere->id,
                'matiere_semestre_id' => $matiere->semestre_id
            ]);
            abort(404, 'Cette matière n\'appartient pas à ce semestre.');
        }

        $fichiers = $matiere->fichiers ?? collect();

        return view('pages.matiere', [
            'semestre' => $semestre,
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
        if (!$etudiant || is_null($etudiant->promotion_id)) {
            $emploisDuTemps = collect();
            return view('pages.emplois-du-temps', compact('emploisDuTemps'))
                ->with('error', $etudiant ? "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration." : "Votre compte n'est pas associé à un profil étudiant.");
        }

        $emploisDuTemps = EmploiDuTemps::with(['semestre'])
            ->whereHas('semestre', function ($query) use ($etudiant) {
                $query->where('promotion_id', $etudiant->promotion_id);
            })
            ->orderByDesc('debut')
            ->get();

        return view('pages.emplois-du-temps', compact('emploisDuTemps'));
    }

    public function displayEmploiDuTemps(EmploiDuTemps $emploiDuTemps) {
        $joursOrder = ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"];
        $joursSql = "'" . implode("','", $joursOrder) . "'";

        $cours = Cours::with(['matiere.unite', 'matiere.enseignant.user', 'salle'])
            ->where('emploi_du_temps_id', $emploiDuTemps->id)
            ->orderByRaw("FIELD(jour, $joursSql)")
            ->orderBy('debut')
            ->get();

        return view('pages.emploi-du-temps', [
            'edt' => $emploiDuTemps,
            'cours' => $cours,
            'joursOrder' => $joursOrder,
        ]);
    }

    public function displayEmploiDuTempsActif() {
        $user = Auth::user();

        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        $etudiant = $user->etudiant;
        if (!$etudiant) {
            $cours = collect();
            return view('pages.emploi-du-temps', compact('cours'))
                ->with('error', "Votre compte n'est pas associé à un profil étudiant.");
        }

        if (is_null($etudiant->promotion_id)) {
            $cours = collect();
            return view('pages.emploi-du-temps', compact('cours'))
                ->with('error', "Vous n'appartenez à aucune promotion. Veuillez contacter l'administration.");
        }

        $promotionId = $etudiant->promotion_id;

        $edtActif = EmploiDuTemps::with(['semestre.promotion'])
            ->where('actif', true)
            ->whereHas('semestre', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })
            ->first();

        if (!$edtActif) {
            $cours = collect();
            return view('pages.emploi-du-temps', compact('cours'))
                ->with('error', "Aucun emploi du temps actif n'a été trouvé pour votre promotion.");
        }

        $joursOrder = ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"];
        $joursSql = "'" . implode("','", $joursOrder) . "'";

        $cours = Cours::with(['matiere.unite', 'matiere.enseignant.user', 'salle'])
            ->where('emploi_du_temps_id', $edtActif->id)
            ->orderByRaw("FIELD(jour, $joursSql)")
            ->orderBy('debut')
            ->get();

        return view('pages.emploi-du-temps', [
            'edt' => $edtActif,
            'cours' => $cours,
            'joursOrder' => $joursOrder,
        ]);
    }
}
