<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ProfanityTrait
{
    public function checkForProfanity($name) {

        $response = Http::get(config('app.purgo_malum_profanity_filter'), [
            'text' => $name
        ]);

        if ($response->body() === 'true') {
            return response()->json(['message' => 'Name cannot contain profanity'], 422);
        }
    }

    public function checkNameForProfanityWhenRegistering($name) {

       return Http::get(config('app.purgo_malum_profanity_filter'), [
            'text' => $name
        ])->body();

    }
}
