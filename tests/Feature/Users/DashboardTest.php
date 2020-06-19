<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->generateAdministrator();
        $this->loginUser();

        $this->getRoute = route('user.dashboard');
    }

    /** @test */
    public function user_is_already_logged_in()
    {
        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function user_sees_the_dashboard_page()
    {
        $this->get($this->getRoute)
            ->assertViewIs('user.dashboard')
            ->assertSee('Welcome '. auth()->user()->username);
    }

    /** @test */
    public function user_may_logout_from_admin_panel()
    {
        $this->assertTrue(auth()->check());

        $this->get(route('user.logout'))
            ->assertRedirect(route('homePage'));

        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function user_redirected_to_home_page_if_not_logged_in()
    {
        auth()->logout();

        $this->get($this->getRoute)
            ->assertRedirect(route('homePage'));
    }
}
