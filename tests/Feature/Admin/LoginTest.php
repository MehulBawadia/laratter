<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->generateAdministrator();

        $this->getRoute = route('admin.login');
        $this->postRoute = route('admin.login.check');
    }

    /** @test */
    public function administrator_exists_in_the_application()
    {
        $this->assertNotNull(\Laratter\User::first());
    }

    /** @test */
    public function administrator_sees_the_login_form()
    {
        $this->withoutExceptionHandling();

        $this->get($this->getRoute)
            ->assertViewIs('admin.login')
            ->assertSee('Login Administrator');
    }

    /** @test */
    public function administrator_can_login_into_admin_panel()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(auth()->guest());

        $this->postJson($this->postRoute, $this->mergeLoginData())
            ->assertJson([
                'status' => 'success',
                'title' => 'Success !',
                'delay' => 3000,
                'message' => 'Logged in successfully. Redirecting...',
                'redirectTo' => route('admin.dashboard')
            ]);

        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function administrator_redirected_to_dashboard_if_already_logged_in()
    {
        auth()->login(\Laratter\User::first());

        $this->get($this->getRoute)
            ->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function administrator_cannot_post_login_credentials_if_already_logged_in()
    {
        auth()->login(\Laratter\User::first());

        $this->postJson($this->postRoute, [])
            ->assertJson([
                'message' => 'This action is unauthorized.'
            ]);
    }

    /** @test */
    public function displays_invalid_credentials_message_if_admin_record_not_found()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(auth()->guest());

        $this->postJson($this->postRoute, $this->mergeLoginData(['usernameOrEmail' => 'nouser']))
            ->assertJson([
                'status' => 'failed',
                'title' => 'Failed !',
                'delay' => 3000,
                'message' => 'Invalid Credentials.'
            ]);

        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function username_or_email_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeLoginData(['usernameOrEmail' => '']))
            ->assertSessionHasErrors(['usernameOrEmail' => 'The username or email field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('usernameOrEmail'),
            'The username or email field is required.'
        );
    }

    /** @test */
    public function password_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeLoginData(['password' => '']))
            ->assertSessionHasErrors(['password' => 'The password field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }

    /**
     * Merge the fake contents of Login form data.
     *
     * @param  array  $data
     * @return array
     */
    private function mergeLoginData($data = [])
    {
        return array_merge([
            'usernameOrEmail' => \Illuminate\Support\Arr::random(['admin', 'admin@example.com']),
            'password'        => 'Password'
        ], $data);
    }
}
