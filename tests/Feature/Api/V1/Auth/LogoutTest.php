<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canLogoutUser(): void
    {
        $user = $this->create('User');

        Passport::actingAs($user);

        $this->postJson(route('api.auth.logout'))
            ->assertJson([
                "status" => true,
                'message' => trans('auth.logout')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertCookieExpired(config('passport.cookie.name'))
            ->assertStatus(200);
    }
}
