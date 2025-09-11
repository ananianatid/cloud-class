<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnrollmentKeyController extends Controller
{
    public function store(Request $request)
    {
        $promotionId = $request->input('promotion_id');

        $key = new EnrollmentKey();
        $key->key = Str::upper(Str::random(10));
        $key->promotion_id = $promotionId;
        $key->save();

        return response()->json(['key' => $key->key], 201);
    }
}

