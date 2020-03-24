<?php

namespace Tests\Feature\Api\V1\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $loggedInUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
        $this->loggedInUser = $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function canUpdateProfile(): void
    {
        $this->loggedInUser->patchJson(route('profile.update'), $data = $this->data())
            ->assertStatus(202)
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.update', ['attribute' => 'Profile'])
            ]);
        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']
        ]);
    }

    /** @test */
    public function cannotUpdateProfileWithWrongInput(): void
    {
        //first_name field is required
        $this->loggedInUser->patchJson(route('profile.update'), $this->data(['first_name' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.required', ['attribute' => 'first name'])
                    ]
                ]
            ]);

        //The first name may not be greater than 100 characters.
        $this->loggedInUser->patchJson(route('profile.update'), $this->data(['first_name' => Str::random(300)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.max.string', ['attribute' => 'first name', 'max' => 100])
                    ]
                ]
            ]);

        //last name field is required
        $this->loggedInUser->patchJson(route('profile.update'), $this->data(['last_name' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.required', ['attribute' => 'last name'])
                    ]
                ]
            ]);

        //The last name may not be greater than 100 characters.
        $this->loggedInUser->patchJson(route('profile.update'), $this->data(['last_name' => Str::random(300)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.max.string', ['attribute' => 'last name', 'max' => 100])
                    ]
                ]
            ]);
    }

    private function data(array $data = []): array
    {
        return [
            'first_name' => $data['first_name'] ?? $this->faker->firstName,
            'last_name' => $data['last_name'] ?? $this->faker->lastName
        ];
    }
}
