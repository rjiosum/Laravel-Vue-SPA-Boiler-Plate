<?php

namespace Tests\Browser\PagesTests\Auth;


use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Register;
use Tests\Browser\Pages\User\Dashboard;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use WithFaker;

    /** @test */
    public function canRegisterAUser(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register)
                ->assertTitle('Register')
                ->assertSeeLink('Sign in')
                ->submit([
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'email' => $this->faker->unique()->safeEmail,
                    'password' => 'password',
                    'password_confirmation' => 'password'
                ])
                ->assertVisited(Dashboard::class)
                ->assertHasCookie(config('passport.cookie.name'), false);
        });
    }
}
