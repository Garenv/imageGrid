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
            return response()->json(['Name cannot contain profanity'], 422);
        }
    }

    public function checkForProfanityWhenRegistering($name) {

        $response = Http::get(config('app.purgo_malum_profanity_filter'), [
            'text' => $name
        ]);

        if ($response->body() === 'true') {
            return redirect('/login')->with('error', "Names cannot contain any profanity");
        }
    }
}
