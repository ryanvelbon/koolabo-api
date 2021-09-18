<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Language;
use App\Models\UserLanguage;


class UserLanguageController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = $request->user();

        // check if user already has this language
        if($user->languages->find($id))
            return response("You already added this language", 200);

        // return 404 HTTP response if language locator is incorrect
        $language = Language::findOrFail($id);

        $user->languages()->attach($language);

        return response("Success. {$language->title} added to your languages", 201);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->languages()->detach($id);

        return response('Language has been removed', 204);
    }
}
