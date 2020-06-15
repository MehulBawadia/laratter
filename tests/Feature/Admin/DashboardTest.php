<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loginAdministrator();

        $this->getRoute = route('admin.dashboard');
    }

    /** @test */
    public function administrator_is_already_logged_in()
    {
        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function administrator_sees_the_dashboard_page()
    {
        $this->get($this->getRoute)
            ->assertViewIs('admin.dashboard')
            ->assertSee('Welcome '. auth()->user()->username);
    }

    /** @test */
    public function administrator_may_logout_from_admin_panel()
    {
        $this->assertTrue(auth()->check());

        $this->get(route('admin.logout'))
            ->assertRedirect(route('homePage'));

        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function administrator_redirected_to_home_page_if_not_logged_in()
    {
        auth()->logout();

        $this->get($this->getRoute)
            ->assertRedirect(route('homePage'));
    }
}
