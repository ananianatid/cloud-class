<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Semestre;
use App\Models\EmploiDuTemps;
use App\Models\Cours;
use App\Models\Livre;
use App\Services\GoogleBooksService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function displayMatiere(Matiere $matiere){
        // Log pour débogage
        Log::info('displayMatiere appelé', [
            'matiere_id' => $matiere->id,
            'matiere_semestre_id' => $matiere->semestre_id
        ]);

        // Récupérer le semestre à partir de la matière
        $semestre = $matiere->semestre;

        if (!$semestre) {
            Log::error('Aucun semestre trouvé pour cette matière', [
                'matiere_id' => $matiere->id,
                'matiere_semestre_id' => $matiere->semestre_id
            ]);
            abort(404, 'Aucun semestre trouvé pour cette matière.');
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

    public function displayBibliotheque(Request $request) {
        $user = Auth::user();

        if ($user->role !== 'etudiant') {
            abort(403, 'Accès non autorisé. Seuls les étudiants peuvent accéder à cette page.');
        }

        $googleBooksService = new GoogleBooksService();
        $livres = collect();
        $searchQuery = $request->get('search', '');

        // Récupérer les livres de la base de données
        $livresDb = Livre::with('categorieLivre')->get();

        // Enrichir avec les données de l'API Google Books
        foreach ($livresDb as $livre) {
            $bookData = $googleBooksService->searchByIsbn($livre->isbn);

            if ($bookData) {
                $livres->push([
                    'id' => $livre->id,
                    'isbn' => $livre->isbn,
                    'categorie' => $livre->categorieLivre->nom ?? 'Non catégorisé',
                    'chemin_fichier' => $livre->chemin_fichier,
                    'api_data' => $bookData,
                    'created_at' => $livre->created_at,
                ]);
            } else {
                // Si pas de données API, utiliser des données par défaut
                $livres->push([
                    'id' => $livre->id,
                    'isbn' => $livre->isbn,
                    'categorie' => $livre->categorieLivre->nom ?? 'Non catégorisé',
                    'chemin_fichier' => $livre->chemin_fichier,
                    'api_data' => [
                        'title' => 'Livre non trouvé',
                        'authors' => ['Auteur inconnu'],
                        'thumbnail' => null,
                        'description' => 'Aucune information disponible pour ce livre.',
                        'info_link' => null,
                        'preview_link' => null,
                    ],
                    'created_at' => $livre->created_at,
                ]);
            }
        }

        // Filtrer par recherche si nécessaire
        if ($searchQuery) {
            $livres = $livres->filter(function ($livre) use ($searchQuery) {
                return stripos($livre['api_data']['title'], $searchQuery) !== false ||
                       stripos(implode(', ', $livre['api_data']['authors']), $searchQuery) !== false ||
                       stripos($livre['categorie'], $searchQuery) !== false ||
                       stripos($livre['isbn'], $searchQuery) !== false;
            });
        }

        return view('pages.bibliotheque.index', compact('livres', 'searchQuery'));
    }
}
