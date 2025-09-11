<?php

namespace Database\Seeders;

use App\Models\EnrollmentKey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnrollmentKeySeeder extends Seeder
{
    public function run(): void
    {
        EnrollmentKey::query()->create([
            'key' => Str::upper(Str::random(10)),
            'promotion_id' => 1,
        ]);
    }
}

