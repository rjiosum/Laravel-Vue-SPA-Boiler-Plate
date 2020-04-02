<?php

namespace Tests\Browser\Pages\User;


use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Dashboard extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/user/dashboard';
    }

    /**
     * @param Browser $browser
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function assert(Browser $browser)
    {
        $browser->waitForLocation($this->url())->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@dropdown-toggle' => '.drop-down #dropdown-toggle',
            '@dropdown-menu-content' => '#dropdown-menu-content',
        ];
    }

    public function clickLogoutLink(Browser $browser)
    {
        $browser->mouseover('@dropdown-toggle')
            ->waitFor('@dropdown-menu-content')
            ->mouseover('@dropdown-menu-content')
            ->waitForText('Logout')
            ->clickLink('Logout')
            ->pause(100);
    }
}
