<?php

namespace Tests\Feature\Api\V1\Auth;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
    }

    /** @test */
    public function canResetPassword(): void
    {
        $this->postJson(route('api.auth.password.reset'), $this->data())
            ->assertJson([
                'status' => true,
                'message' => trans('passwords.reset')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function cannotResetPasswordWithWrongInput(): void
    {
        //token field is required
        $this->postJson(route('api.auth.password.reset'), $this->data(['token' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'token' => [
                        trans('validation.required', ['attribute' => 'token'])
                    ]
                ]
            ]);

        //Must be a valid token
        $this->postJson(route('api.auth.password.reset'), $this->data(['token' => 'invalid']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('passwords.token')
                    ]
                ]
            ]);

        //email field is required
        $this->postJson(route('api.auth.password.reset'), $this->data(['email' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email should be valid
        $this->postJson(route('api.auth.password.reset'), $this->data(['email' => '123fcd']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //password field is required
        $this->postJson(route('api.auth.password.reset'), $this->data(['password' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.required', ['attribute' => 'password'])
                    ]
                ]
            ]);

        //The password must be at least 8 characters.
        $this->postJson(route('api.auth.password.reset'), $this->data(['password' => '123', 'password_confirmation' => '123']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.min.string', ['attribute' => 'password', 'min' => 8])
                    ]
                ]
            ]);

        //The password confirmation does not match.
        $this->postJson(route('api.auth.password.reset'), $this->data(['password' => '123456789']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.confirmed', ['attribute' => 'password'])
                    ]
                ]
            ]);

    }

    private function data(array $data = []): array
    {
        return [
            'token' => $data['token'] ?? $this->token($this->user),
            'email' => $data['email'] ?? $this->user->email,
            'password' => $data['password'] ?? 'new-password',
            'password_confirmation' => $data['password_confirmation'] ?? 'new-password'
        ];
    }

    private function token($user)
    {
        return Password::broker()->createToken($user);
    }
}