<?php

use App\Models\User;
use App\Models\Semestre;
use App\Models\EmploiDuTemps;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('denies non-student access to protected pages', function () {
    $user = User::factory()->create(['role' => 'enseignant']);
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertStatus(403);
});

it('allows student to access semestres and emploi du temps routes', function () {
    $user = User::factory()->create(['role' => 'etudiant']);
    $this->actingAs($user);

    $this->get(route('semestres'))->assertStatus(200);
    $this->get(route('emplois-du-temps'))->assertStatus(200);
});

