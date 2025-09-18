<?php

use App\Models\User;

it('responds for student on active timetable route', function () {
    $user = User::factory()->create(['role' => 'etudiant']);
    $this->actingAs($user);

    $response = $this->get(route('emploi-du-temps-actif'));
    $response->assertStatus(200);
});

