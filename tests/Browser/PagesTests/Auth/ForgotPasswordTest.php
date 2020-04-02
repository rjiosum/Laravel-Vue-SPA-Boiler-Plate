<?php

namespace Tests\Browser\PagesTests\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\ForgotPassword;
use Tests\DuskTestCase;

class ForgotPasswordTest extends DuskTestCase
{
    /** @test */
    public function canSendPasswordResetEmail(): void
    {
        $user = $this->create('User');

        $this->browse(function(Browser $browser) use ($user){
            $browser->visit(new ForgotPassword)
                ->pause(1000)
                ->assertTitle('Forgot Password')
                ->assertSeeLink('Sign in')
                ->submit($user->email)
                ->waitForText(trans('passwords.sent'))
                ->assertSee(trans('passwords.sent'));
        });
    }
}
