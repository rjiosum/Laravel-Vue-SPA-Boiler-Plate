<?php

namespace Tests\Feature\Api\V1\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function canLoginUserWithValidCredentials(): void
    {
        $user = $this->create('User');

        $this->postJson(route('login'), [
            'email' => $user->email, 'password' => 'password'
        ])
            ->assertJson([
                "status" => true,
                "user" => [
                    "name" => $user->first_name . ' ' . $user->last_name,
                    "email" => $user->email
                ]
            ])
            ->assertJsonStructure([
                'status',
                'user' => ['uuid', 'name', 'email', 'avatar']
            ])
            ->assertCookie(config('passport.cookie.name'))
            ->assertStatus(200);
    }

    /** @test */
    public function willNotLoginUserWithInvalidCredentials(): void
    {
        $user = $this->create('User');

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong'
        ])
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => [trans('auth.failed')]
                ]
            ])
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertStatus(422);
    }
}
