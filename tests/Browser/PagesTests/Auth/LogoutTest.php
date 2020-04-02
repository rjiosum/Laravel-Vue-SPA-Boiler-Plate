<?php

namespace Tests\Browser\PagesTests\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Login;
use Tests\Browser\Pages\User\Dashboard;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    /** @test */
    public function canLogOutUser()
    {
        $user = $this->create('User');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new Login)
                ->submit($user->email, 'password')
                ->on(new Dashboard)
                ->clickLogoutLink()
                ->assertVisited(Login::class);
        });
    }
}
