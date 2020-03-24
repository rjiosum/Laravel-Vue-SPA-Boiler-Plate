<?php

namespace Tests\Feature\Api\V1\Auth;


use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSendPasswordResetEmail(): void
    {
        $user = $this->create('User');

        Notification::fake();

        $this->postJson(route('password.email'), ['email' => $user->email])
            ->assertJson([
                'status' => true,
                'message' => trans('passwords.sent')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(200);

        Notification::assertSentTo(
            [$user], ResetPasswordNotification::class
        );

        $this->assertNotNull($token = DB::table('password_resets')->first());

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /** @test */
    public function cannotSendResetPasswordNotificationWithWrongInput(): void
    {
        //email field is required
        $this->postJson(route('password.email'), ['email' => ''])
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
        $this->postJson(route('password.email'), ['email' => '123fcd'])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute'=>'email'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function willThrowCannotFindUserIfEmailAddressIsInvalid(): void
    {
        $this->postJson(route('password.email'), ['email' => 'nouser@example.com'])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('passwords.user')
                    ]
                ]
            ]);
    }
}