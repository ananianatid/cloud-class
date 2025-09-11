<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
USE \App\Models\Filiere;
class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Filiere::insert([
            [
                'nom' => 'Intelligence Artificielle et Big Data',
                'code' => 'IABD',
                'description' => 'La filière Intelligence Artificielle et Big Data forme des spécialistes en analyse de données massives et en conception de systèmes intelligents.'
            ],
            [
                'nom' => 'Systèmes et Réseaux Informatiques',
                'code' => 'SR',
                'description' => 'La filière Systèmes et Réseaux Informatiques étudie les infrastructures réseaux, la sécurité et l’administration des systèmes informatiques.'
            ],
            [
                'nom' => 'Génie Logiciel',
                'code' => 'GL',
                'description' => 'La filière Génie Logiciel forme des développeurs et ingénieurs capables de concevoir, développer et maintenir des logiciels complexes.'
            ],
            [
                'nom' => 'Génie Civil',
                'code' => 'GC',
                'description' => 'La filière Génie Civil forme des ingénieurs en conception, construction et gestion d’ouvrages de génie civil.'
            ],
            [
                'nom' => 'Comptabilité Finance',
                'code' => 'CF',
                'description' => 'La filière Comptabilité Finance prépare des spécialistes en gestion financière, analyse comptable et audit.'
            ],
            [
                'nom' => 'Gestion Commerciale',
                'code' => 'GCm',
                'description' => 'La filière Gestion Commerciale forme des professionnels en management, marketing et techniques de vente.'
            ],
            [
                'nom' => 'Gestion des Ressources Humaines',
                'code' => 'GRH',
                'description' => 'La filière GRH prépare des cadres pour la gestion, le développement et la formation des ressources humaines.'
            ],
            [
                'nom' => 'Assistant Administratif',
                'code' => 'AA',
                'description' => 'La filière Assistant Administratif forme des techniciens spécialisés dans le soutien administratif et organisationnel.'
            ],
            [
                'nom' => 'Communication Digitale',
                'code' => 'CD',
                'description' => 'La filière Communication Digitale forme des experts en communication numérique, réseaux sociaux et création de contenu.'
            ],
            [
                'nom' => 'Communication des Organisations',
                'code' => 'CO',
                'description' => 'La filière Communication des Organisations développe des compétences en stratégie de communication interne et externe.'
            ],
            [
                'nom' => 'Publicité et Arts Graphiques',
                'code' => 'PAG',
                'description' => 'La filière Publicité et Arts Graphiques forme des créatifs en conception graphique et publicité.'
            ],
            [
                'nom' => 'Logistique et Transport',
                'code' => 'LT',
                'description' => 'La filière Logistique et Transport forme des spécialistes en gestion de flux, transport et chaîne logistique.'
            ],
            [
                'nom' => 'Master Génie Logiciel',
                'code' => 'MGL',
                'description' => 'Le master en Génie Logiciel prépare des experts en architecture logicielle et gestion de projets informatiques.'
            ],
            [
                'nom' => 'Master Systèmes et Réseaux Informatiques',
                'code' => 'MSRI',
                'description' => 'Le master en Systèmes et Réseaux Informatiques forme des spécialistes avancés en réseaux, cybersécurité et administration systèmes.'
            ]
        ]);
    }
}
