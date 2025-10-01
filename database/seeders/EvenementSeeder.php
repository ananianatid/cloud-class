<?php

namespace Database\Seeders;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un admin pour created_by
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first(); // Fallback sur le premier utilisateur
        }

        $evenements = [
            [
                'titre' => 'Journée Portes Ouvertes',
                'corps' => '## Journée Portes Ouvertes 2024

Venez découvrir notre école et nos formations !

### Programme :
- **9h00** : Accueil des visiteurs
- **9h30** : Présentation des filières
- **11h00** : Visite des laboratoires
- **14h00** : Rencontre avec les étudiants
- **16h00** : Questions/Réponses

### Contact
Pour plus d\'informations : contact@ecole.fr',
                'date' => Carbon::now()->addDays(15)->toDateString(),
                'heure' => '09:00',
                'couleur' => '#3B82F6', // Bleu
            ],
            [
                'titre' => 'Examen Final - Session 1',
                'corps' => '## Examen Final - Session 1

**Important** : Tous les étudiants doivent être présents.

### Matériel autorisé :
- Calculatrice scientifique
- Feuilles de brouillon
- Stylos bleus ou noirs

### Horaires :
- **8h00** : Accueil
- **8h30** : Début des épreuves
- **12h00** : Fin des épreuves

### Salles :
- Salle 101 : Génie Logiciel
- Salle 102 : Génie Civil
- Salle 103 : Systèmes',
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'heure' => '08:30',
                'couleur' => '#EF4444', // Rouge
            ],
            [
                'titre' => 'Conférence IA et Éthique',
                'corps' => '## Conférence : Intelligence Artificielle et Éthique

**Intervenant** : Dr. Marie Dubois, Directrice de Recherche chez TechCorp

### Thèmes abordés :
- L\'éthique dans le développement d\'IA
- Les enjeux de la transparence algorithmique
- L\'impact social de l\'intelligence artificielle
- Questions d\'avenir et perspectives

### Public cible :
- Étudiants en Génie Logiciel
- Enseignants et chercheurs
- Professionnels du secteur

### Inscription :
Gratuite mais obligatoire via le portail étudiant.',
                'date' => Carbon::now()->addDays(45)->toDateString(),
                'heure' => '14:00',
                'couleur' => '#8B5CF6', // Violet
            ],
            [
                'titre' => 'Remise des Diplômes 2024',
                'corps' => '## Cérémonie de Remise des Diplômes 2024

Félicitations à tous nos diplômés !

### Programme de la journée :
- **10h00** : Accueil des familles
- **10h30** : Cérémonie officielle
- **12h00** : Cocktail de clôture
- **14h00** : Photos de groupe

### Tenue :
Tenue de cérémonie obligatoire (toge fournie)

### Invités :
Chaque diplômé peut inviter 2 personnes maximum.

### Photos :
Service photo professionnel disponible sur place.',
                'date' => Carbon::now()->addDays(60)->toDateString(),
                'heure' => '10:00',
                'couleur' => '#F59E0B', // Orange
            ],
            [
                'titre' => 'Réunion Pédagogique',
                'corps' => '## Réunion Pédagogique - Semestre 5

**Ordre du jour** :
1. Bilan du semestre en cours
2. Préparation des examens
3. Planning des vacances
4. Projets de fin d\'études
5. Divers

### Participants :
- Direction pédagogique
- Enseignants du semestre 5
- Représentants étudiants

### Documents à préparer :
- Rapport d\'activité
- Statistiques de réussite
- Propositions d\'amélioration',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'heure' => '15:00',
                'couleur' => '#10B981', // Vert
            ],
            [
                'titre' => 'Fête de l\'École',
                'corps' => '## Fête de l\'École 2024

Venez célébrer avec nous !

### Animations :
- **Concerts** : Groupes étudiants
- **Food trucks** : Restauration sur place
- **Jeux** : Tournois et animations
- **Expositions** : Projets étudiants

### Horaires :
- **18h00** : Ouverture
- **20h00** : Concert principal
- **22h00** : Feux d\'artifice
- **23h00** : Clôture

### Entrée :
Gratuite pour tous les étudiants et personnels',
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'couleur' => '#EC4899', // Rose
            ],
        ];

        foreach ($evenements as $evenement) {
            Evenement::create([
                'titre' => $evenement['titre'],
                'corps' => $evenement['corps'],
                'date' => $evenement['date'],
                'heure' => $evenement['heure'] ?? null,
                'couleur' => $evenement['couleur'],
                'created_by' => $admin->id,
            ]);
        }
    }
}
