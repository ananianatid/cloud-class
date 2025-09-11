<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\UniteEnseignement;

class UniteEnseignementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UniteEnseignement::insert([
            [
                'nom' => 'Algorithmique',
                'code' => 'ALG101',
                'description' => 'Introduction à l\'algorithmique',
                'credits' => 3,
            ],
            [
                'nom' => 'Francais',
                'code' => 'FRA101',
                'description' => 'Cours de français',
                'credits' => 2,
            ],
            [
                'nom' => 'Programation',
                'code' => 'PRG101',
                'description' => 'Bases de la programmation',
                'credits' => 3,
            ],

            [
                'nom' => 'Électronique Général',
                'code' => 'ELE101',
                'description' => 'Électronique générale',
                'credits' => 3,
            ],

            [
                'nom' => 'Analyse',
                'code' => 'ANA101',
                'description' => 'Analyse mathématique',
                'credits' => 3,
            ],
            [
                'nom' => 'IHM',
                'code' => 'IHM101',
                'description' => 'Interfaces Homme-Machine',
                'credits' => 2,
            ],
            [
                'nom' => 'ATO',
                'code' => 'ATO101',
                'description' => 'Automates et théorie des objets',
                'credits' => 2,
            ],

            [
                'nom' => 'Systeme d\'exploitation',
                'code' => 'SYS101',
                'description' => 'Systèmes d\'exploitation',
                'credits' => 3,
            ],
            [
                'nom' => 'algebre',
                'code' => 'ALG102',
                'description' => 'Algèbre linéaire',
                'credits' => 2,
            ],
            [
                'nom' => 'POO',
                'code' => 'POO101',
                'description' => 'Programmation orientée objet',
                'credits' => 3,
            ],
            [
                'nom' => 'programmation-web',
                'code' => 'WEB101',
                'description' => 'Programmation web',
                'credits' => 2,
            ],
            [
                'nom' => 'anglais',
                'code' => 'ANG101',
                'description' => 'Cours d\'anglais',
                'credits' => 2,
            ],
            [
                'nom' => 'environnements de telecommunication',
                'code' => 'TEL101',
                'description' => 'Environnements de télécommunication',
                'credits' => 2,
            ],
            [
                'nom' => 'probabilites et statistiques',
                'code' => 'PRO101',
                'description' => 'Probabilités et statistiques',
                'credits' => 2,
            ],
            [
                'nom' => 'Algorithmique et complexite',
                'code' => 'ALG201',
                'description' => 'Algorithmique et complexité',
                'credits' => 3,
            ],
            [
                'nom' => 'Recherche Operationnelle',
                'code' => 'RO101',
                'description' => 'Recherche opérationnelle',
                'credits' => 2,
            ],
            [
                'nom' => 'RECUEIL DE COURS ATO ET TRAITEMENT DU SIGNAL',
                'code' => 'ATO201',
                'description' => 'Recueil de cours ATO et traitement du signal',
                'credits' => 2,
            ],
            [
                'nom' => 'Evolutivite des reseaux_CCNA3',
                'code' => 'CCN103',
                'description' => 'Évolutivité des réseaux (CCNA3)',
                'credits' => 2,
            ],
            [
                'nom' => 'Applications de bureau et designs patterns',
                'code' => 'APP101',
                'description' => 'Applications de bureau et design patterns',
                'credits' => 2,
            ],
            [
                'nom' => 'Les reseaux dacces filiaires',
                'code' => 'RES101',
                'description' => 'Les réseaux d\'accès filaires',
                'credits' => 2,
            ],
            [
                'nom' => 'Analyse Numerique',
                'code' => 'ANA201',
                'description' => 'Analyse numérique',
                'credits' => 2,
            ],
            [
                'nom' => 'Initiation au Genie Logiciel',
                'code' => 'GL101',
                'description' => 'Initiation au génie logiciel',
                'credits' => 2,
            ],
        ]);
    }
}
