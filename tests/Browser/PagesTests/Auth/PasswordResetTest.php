<?php

namespace Tests\Browser\PagesTests\Auth;

use Illuminate\Support\Facades\Password;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\PasswordReset;
use Tests\DuskTestCase;

class PasswordResetTest extends DuskTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
    }

    /** @test */
    public function canResetPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new PasswordReset($this->token()))
                ->waitForText('Reset your password.')
                ->submit($this->user->email, 'new-password', 'new-password')
                ->waitForText(trans('passwords.reset'), 5)
                ->assertSee(trans('passwords.reset'));
        });
    }

    private function token()
    {
        return Password::broker()->createToken($this->user);
    }
}
