<?php

namespace App;

trait TeacherPermissions
{
    /**
     * Vérifier si l'utilisateur actuel est un enseignant
     */
    protected function isTeacher(): bool
    {
        return auth()->user() && auth()->user()->role === 'enseignant';
    }

    /**
     * Vérifier si l'utilisateur actuel est un administrateur
     */
    protected function isAdmin(): bool
    {
        return auth()->user() && auth()->user()->role === 'administrateur';
    }

    /**
     * Obtenir l'ID de l'enseignant actuel
     */
    protected function getCurrentTeacherId(): ?int
    {
        if (!$this->isTeacher()) {
            return null;
        }

        return session('current_enseignant_id');
    }

    /**
     * Vérifier si une ressource doit être visible pour les enseignants
     */
    protected function shouldHideFromTeachers(): bool
    {
        return $this->isTeacher();
    }

    /**
     * Obtenir les matières de l'enseignant actuel
     */
    protected function getTeacherMatieres()
    {
        $enseignantId = $this->getCurrentTeacherId();
        if (!$enseignantId) {
            return collect();
        }

        return \App\Models\Matiere::where('enseignant_id', $enseignantId)->get();
    }

    /**
     * Filtrer une requête pour ne montrer que les matières de l'enseignant
     */
    protected function filterByTeacherMatieres($query)
    {
        if ($this->isTeacher()) {
            $enseignantId = $this->getCurrentTeacherId();
            if ($enseignantId) {
                $query->where('enseignant_id', $enseignantId);
            }
        }

        return $query;
    }
}
