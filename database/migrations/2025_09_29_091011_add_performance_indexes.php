<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Index pour la table users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!$this->indexExists('users', 'users_role_index')) {
                    $table->index('role');
                }
                if (!$this->indexExists('users', 'users_email_index')) {
                    $table->index('email');
                }
            });
        }

        // Index pour la table matieres
        if (Schema::hasTable('matieres')) {
            Schema::table('matieres', function (Blueprint $table) {
                if (!$this->indexExists('matieres', 'matieres_enseignant_id_index')) {
                    $table->index('enseignant_id');
                }
                if (!$this->indexExists('matieres', 'matieres_semestre_id_index')) {
                    $table->index('semestre_id');
                }
                if (!$this->indexExists('matieres', 'matieres_unite_id_index')) {
                    $table->index('unite_id');
                }
            });
        }

        // Index pour la table cours (éviter les conflits avec les index existants)
        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                if (!$this->indexExists('cours', 'cours_matiere_id_index')) {
                    $table->index('matiere_id');
                }
                if (!$this->indexExists('cours', 'cours_salle_id_index')) {
                    $table->index('salle_id');
                }
                if (!$this->indexExists('cours', 'cours_jour_index')) {
                    $table->index('jour');
                }
                if (!$this->indexExists('cours', 'cours_type_index')) {
                    $table->index('type');
                }
            });
        }

        // Index pour la table fichiers
        if (Schema::hasTable('fichiers')) {
            Schema::table('fichiers', function (Blueprint $table) {
                if (!$this->indexExists('fichiers', 'fichiers_matiere_id_index')) {
                    $table->index('matiere_id');
                }
                if (!$this->indexExists('fichiers', 'fichiers_ajoute_par_index')) {
                    $table->index('ajoute_par');
                }
                if (!$this->indexExists('fichiers', 'fichiers_categorie_index')) {
                    $table->index('categorie');
                }
                if (!$this->indexExists('fichiers', 'fichiers_visible_index')) {
                    $table->index('visible');
                }
            });
        }

        // Index pour la table etudiants
        if (Schema::hasTable('etudiants')) {
            Schema::table('etudiants', function (Blueprint $table) {
                if (!$this->indexExists('etudiants', 'etudiants_user_id_index')) {
                    $table->index('user_id');
                }
                if (!$this->indexExists('etudiants', 'etudiants_promotion_id_index')) {
                    $table->index('promotion_id');
                }
            });
        }

        // Index pour la table enseignants
        if (Schema::hasTable('enseignants')) {
            Schema::table('enseignants', function (Blueprint $table) {
                if (!$this->indexExists('enseignants', 'enseignants_user_id_index')) {
                    $table->index('user_id');
                }
                if (!$this->indexExists('enseignants', 'enseignants_statut_index')) {
                    $table->index('statut');
                }
            });
        }
    }

    /**
     * Vérifier si un index existe
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($table);
            return array_key_exists($index, $indexes);
        } catch (\Exception $e) {
            // Si la méthode n'existe pas, on assume que l'index n'existe pas
            return false;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les index seulement s'ils existent
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if ($this->indexExists('users', 'users_role_index')) {
                    $table->dropIndex('users_role_index');
                }
                if ($this->indexExists('users', 'users_email_index')) {
                    $table->dropIndex('users_email_index');
                }
            });
        }

        if (Schema::hasTable('matieres')) {
            Schema::table('matieres', function (Blueprint $table) {
                if ($this->indexExists('matieres', 'matieres_enseignant_id_index')) {
                    $table->dropIndex('matieres_enseignant_id_index');
                }
                if ($this->indexExists('matieres', 'matieres_semestre_id_index')) {
                    $table->dropIndex('matieres_semestre_id_index');
                }
                if ($this->indexExists('matieres', 'matieres_unite_id_index')) {
                    $table->dropIndex('matieres_unite_id_index');
                }
            });
        }

        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                if ($this->indexExists('cours', 'cours_matiere_id_index')) {
                    $table->dropIndex('cours_matiere_id_index');
                }
                if ($this->indexExists('cours', 'cours_salle_id_index')) {
                    $table->dropIndex('cours_salle_id_index');
                }
                if ($this->indexExists('cours', 'cours_jour_index')) {
                    $table->dropIndex('cours_jour_index');
                }
                if ($this->indexExists('cours', 'cours_type_index')) {
                    $table->dropIndex('cours_type_index');
                }
            });
        }

        if (Schema::hasTable('fichiers')) {
            Schema::table('fichiers', function (Blueprint $table) {
                if ($this->indexExists('fichiers', 'fichiers_matiere_id_index')) {
                    $table->dropIndex('fichiers_matiere_id_index');
                }
                if ($this->indexExists('fichiers', 'fichiers_ajoute_par_index')) {
                    $table->dropIndex('fichiers_ajoute_par_index');
                }
                if ($this->indexExists('fichiers', 'fichiers_categorie_index')) {
                    $table->dropIndex('fichiers_categorie_index');
                }
                if ($this->indexExists('fichiers', 'fichiers_visible_index')) {
                    $table->dropIndex('fichiers_visible_index');
                }
            });
        }

        if (Schema::hasTable('etudiants')) {
            Schema::table('etudiants', function (Blueprint $table) {
                if ($this->indexExists('etudiants', 'etudiants_user_id_index')) {
                    $table->dropIndex('etudiants_user_id_index');
                }
                if ($this->indexExists('etudiants', 'etudiants_promotion_id_index')) {
                    $table->dropIndex('etudiants_promotion_id_index');
                }
            });
        }

        if (Schema::hasTable('enseignants')) {
            Schema::table('enseignants', function (Blueprint $table) {
                if ($this->indexExists('enseignants', 'enseignants_user_id_index')) {
                    $table->dropIndex('enseignants_user_id_index');
                }
                if ($this->indexExists('enseignants', 'enseignants_statut_index')) {
                    $table->dropIndex('enseignants_statut_index');
                }
            });
        }
    }
};
