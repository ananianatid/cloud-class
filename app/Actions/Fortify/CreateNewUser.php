<?php

namespace App\Actions\Fortify;

use App\Models\EnrollmentKey;
use App\Models\Etudiant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', Rule::in(['M','F'])],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
            'enrollment_key' => ['nullable', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->after(function ($validator) use ($input) {
            $keyValue = $input['enrollment_key'] ?? null;
            if (!$keyValue) {
                return;
            }
            $key = EnrollmentKey::query()->where('key', $keyValue)->first();
            if (!$key || !$key->isUsable()) {
                $validator->errors()->add('enrollment_key', __('Clé d\'enrôlement invalide ou expirée.'));
            }
        })->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'sexe' => $input['sexe'] ?? 'M',
                'role' => 'etudiant',
                'password' => Hash::make($input['password']),
            ]);

            $keyValue = $input['enrollment_key'] ?? null;
            if ($keyValue) {
                $key = EnrollmentKey::where('key', $keyValue)->lockForUpdate()->first();

                // Re-check usability under lock
                if (!$key || !$key->isUsable()) {
                    abort(422, __('Clé d\'enrôlement invalide ou expirée.'));
                }

                // Create Etudiant and assign promotion
                Etudiant::query()->create([
                    'user_id' => $user->id,
                    'promotion_id' => $key->promotion_id,
                    'naissance' => now(),
                    'statut' => 'actif',
                ]);

                // Consume key
                $key->forceFill([
                    'used_by' => $user->id,
                    'used_at' => now(),
                ])->save();
            }

            return $user;
        });
    }
}
