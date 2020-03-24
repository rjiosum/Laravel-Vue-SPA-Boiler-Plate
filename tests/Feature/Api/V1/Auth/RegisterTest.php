<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function canRegisterAUserIfEmailVerificationIsNotRequired(): void
    {

        if (new User instanceof MustVerifyEmail) {
            $this->markTestSkipped(
                'Registration requires email verification'
            );
        }

        $this->postJson(route('api.auth.register'), $data = $this->data())
            ->assertJson([
                "status" => true,
                "user" => [
                    "name" => $data['first_name'] . ' ' . $data['last_name'],
                    "email" => $data['email']
                ]
            ])
            ->assertJsonStructure([
                'status',
                'user' => ['uuid', 'name', 'email', 'avatar']
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

    }

    ///** @test */
    public function canRegisterAUserIfEmailVerificationIsRequired(): void
    {

        if (!new User instanceof MustVerifyEmail) {
            $this->markTestSkipped(
                'Registration does not require email verification'
            );
        }

        $this->postJson(route('api.auth.register'), $data = $this->data())
            ->assertJson([
                "status" => true,
                "verify" => true,
                "message" => trans('verification.sent')
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

    }

    /** @test */
    public function willThrowErrorsIfUserRegistersWithWrongInputData(): void
    {
        //first_name field is required
        $this->postJson(route('api.auth.register'), $this->data(['first_name' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.required', ['attribute'=>'first name'])
                    ]
                ]
            ]);

        //The first name may not be greater than 100 characters.
        $this->postJson(route('api.auth.register'), $this->data(['first_name' => Str::random(300)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.max.string', ['attribute'=>'first name', 'max'=>100])
                    ]
                ]
            ]);

        //last name field is required
        $this->postJson(route('api.auth.register'), $this->data(['last_name' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.required', ['attribute'=>'last name'])
                    ]
                ]
            ]);

        //The last name may not be greater than 100 characters.
        $this->postJson(route('api.auth.register'), $this->data(['last_name' => Str::random(300)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.max.string', ['attribute'=>'last name', 'max'=>100])
                    ]
                ]
            ]);

        //email field is required
        $this->postJson(route('api.auth.register'), $this->data(['email' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email can have max 255 character
        $this->postJson(route('api.auth.register'), $this->data(['email' => Str::random(260).'@yahoo.com']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.max.string', ['attribute'=>'email', 'max'=>255])
                    ]
                ]
            ]);
        //email should be valid
        $this->postJson(route('api.auth.register'), $this->data(['email' => '123fcd']))
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
        $this->postJson(route('api.auth.register'), $this->data(['password' => '']))
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
        $this->postJson(route('api.auth.register'), $this->data(['password' => '123', 'password_confirmation' => '123']))
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
        $this->postJson(route('api.auth.register'), $this->data(['password' => '123456789']))
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
            'first_name' => $data['first_name'] ?? $this->faker->firstName,
            'last_name' => $data['last_name'] ?? $this->faker->lastName,
            'email' => $data['email'] ?? $this->faker->unique()->safeEmail,
            'password' => $data['password'] ?? 'password',
            'password_confirmation' => $data['password_confirmation'] ?? 'password'
        ];
    }

}
