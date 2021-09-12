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

        // otherwise proceed and add language to profile
        UserLanguage::create([
            'user_id' => $user->id,
            'language_id' => $language->id
        ]);

        return response("Success. {$language->title} added to your languages", 201);
    }

    public function destroy(Request $request, $id)
    {
        UserLanguage::where('user_id', $request->user()->id)
                        ->where('language_id', $id)
                        ->firstOrFail()
                        ->delete();

        return response('Language has been removed', 204);
    }
}
