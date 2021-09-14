<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Language;
use App\Models\UserLanguage;

class UserLanguageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_add_language_to_profile()
    {
        $this->seed();
        $user = User::factory()->create();
        $language = Language::inRandomOrder()->first();
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/users/me/languages/{$language->id}");
        $response->assertStatus(201);
    }

    public function test_user_can_add_multiple_languages_to_profile()
    {
        $this->seed();
        $user = User::factory()->create();
        $n = 5;
        $languages = Language::inRandomOrder()->take($n)->get();

        Sanctum::actingAs($user, ['*']);
        foreach($languages as $language) {
            $response = $this->json('POST', "/api/users/me/languages/{$language->id}");
            $response->assertStatus(201);
        }
        $this->assertEquals($user->refresh()->languages->count(), $n);
    }

    public function test_user_can_remove_language_from_profile()
    {
        $this->seed();
        $user = User::factory()->create();
        $n = 10;
        $languages = Language::inRandomOrder()->take($n)->get();
        foreach($languages as $language) {
            UserLanguage::create([
                'user_id' => $user->id,
                'language_id' => $language->id
            ]);
        }
        $this->assertEquals($user->refresh()->languages->count(), $n);
        Sanctum::actingAs($user, ['*']);
        $this->json('DELETE', "/api/users/me/languages/{$languages[0]->id}");
        $this->assertEquals($user->refresh()->languages->count(), $n-1);
    }

    public function SKIP_post_request_is_idempotent()
    {
        // pending
    }
}
