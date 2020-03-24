<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canResendVerificationLink(): void
    {
        $user = $this->create('User', 1, ['email_verified_at' => null]);

        Notification::fake();

        $this->postJson(route('verification.resend'), ['email' => $user->email])
            ->assertJson([
                'status' => true,
                'message' => trans('verification.sent')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(200);

        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }

    /** @test */
    public function cannotResendVerificationLinkWithWrongInput(): void
    {
        //email field is required
        $this->postJson(route('verification.resend'), ['email' => ''])
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
        $this->postJson(route('verification.resend'), ['email' => Str::random(260) . '@gmail.com'])
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
        $this->postJson(route('verification.resend'), ['email' => '123fcd'])
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
    public function willThrowUserNotFoundErrorIfEmailAddressIsNotFound(): void
    {
        $this->postJson(route('verification.resend'), ['email' => 'nouser@example.com'])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('verification.user')
                    ]
                ]
            ]);
    }

    /** @test */
    public function willNotResendVerificationLinkIfUserHasVerifiedEmail(): void
    {
        $user = $this->create('User');
        $this->postJson(route('verification.resend'), ['email' => $user->email])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('verification.already_verified')
                    ]
                ]
            ]);
    }

    /** @test */
    public function canVerifyUserEmailAddress(): void
    {
        $user = $this->create('User', 1, ['email_verified_at' => null]);

        Event::fake();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->getJson($url)
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
                'message' => trans('verification.verified')
            ]);

        Event::assertDispatched(Verified::class, function (Verified $e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function willThrowErrorIfUserIdSignatureIsInvalid(): void
    {
        $user = $this->create('User', 1, ['email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id - 2,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->getJson($url)
            ->assertStatus(400)
            ->assertExactJson([
                'status' => false,
                'message' => trans('verification.user')
            ]);

    }

    /** @test */
    public function willThrowErrorIfUserEmailSignatureIsInvalid(): void
    {
        $user = $this->create('User', 1, ['email_verified_at' => null]);
        Passport::actingAs($user);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1('nouser@example.com'),
            ]
        );

        $this->getJson($url)
            ->assertStatus(400)
            ->assertExactJson([
                'status' => false,
                'message' => trans('verification.invalid')
            ]);

    }

    /** @test */
    public function willNotVerifyIfUserHasVerifiedEmail(): void
    {
        $user = $this->create('User');

        Passport::actingAs($user);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->getJson($url)
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
                'message' => trans('verification.already_verified')
            ]);
    }
}