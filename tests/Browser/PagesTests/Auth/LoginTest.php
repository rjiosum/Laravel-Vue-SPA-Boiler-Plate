<?php

namespace Tests\Browser\PagesTests\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Login;
use Tests\Browser\Pages\User\Dashboard;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
    }

    /** @test */
    public function canLoginWithValidCredentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->assertTitle('Login')
                ->assertSeeLink('Sign Up')
                ->assertSeeLink('Forgot Your Password?')
                ->submit($this->user->email, 'password')
                ->assertVisited(Dashboard::class)
                ->assertHasCookie(config('passport.cookie.name'), false);
        });
    }

    /** @test */
    public function cannotLoginWithInvalidCredentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->submit($this->user->email, 'wrong-password')
                ->waitForText(trans('auth.failed'), 5)
                ->assertSee(trans('auth.failed'));
        });
    }

}
