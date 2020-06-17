<?php

namespace Tests\Feature\Admin\AccountSettings;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loginAdministrator(['password' => bcrypt('Password')]);

        $this->getRoute = route('admin.accountSettings');
        $this->postRoute = route('admin.accountSettings.changePassword');
    }

    /** @test */
    public function administrator_is_already_logged_in()
    {
        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function administrator_sees_the_account_settings_page()
    {
        $this->get($this->getRoute)
            ->assertViewIs('admin.account-settings')
            ->assertSee('Account Settings')
            ->assertSee('Change Password');
    }

    /** @test */
    public function administrator_can_update_their_password()
    {
        $this->patch($this->postRoute, $this->mergePasswordData(['new_password' => 'Secret']))
            ->assertJson([
                'status' => 'success',
                'title' => 'Success !',
                'delay' => 3000,
                'message' => 'Password updated successfully.'
            ]);
    }

    /** @test */
    public function administrator_sees_invalid_current_password_if_it_is_invaid()
    {
        $this->patch($this->postRoute, $this->mergePasswordData(['current_password' => 'Secret']))
            ->assertJson([
                'status' => 'failed',
                'title' => 'Failed !',
                'delay' => 3000,
                'message' => 'Invalid current password.'
            ]);
    }

    /** @test */
    public function current_password_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergePasswordData(['current_password' => '']))
            ->assertSessionHasErrors(['current_password' => 'The current password field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('current_password'),
            'The current password field is required.'
        );
    }

    /** @test */
    public function new_password_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergePasswordData(['new_password' => '']))
            ->assertSessionHasErrors(['new_password' => 'The new password field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('new_password'),
            'The new password field is required.'
        );
    }

    /** @test */
    public function repeat_new_password_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergePasswordData(['repeat_new_password' => '']))
            ->assertSessionHasErrors(['repeat_new_password' => 'The repeat new password field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_new_password'),
            'The repeat new password field is required.'
        );
    }

    /** @test */
    public function repeat_new_password_and_new_password_must_be_same()
    {
        $data = [
            'new_password' => 'Secret',
            'repeat_new_password' => 'Password',
        ];

        $this->patch($this->postRoute, $this->mergePasswordData($data))
            ->assertSessionHasErrors(['repeat_new_password' => 'The repeat new password and new password must match.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_new_password'),
            'The repeat new password and new password must match.'
        );
    }

    /**
     * Merge the default data with dummy data.
     *
     * @param  array  $data
     * @return array
     */
    protected function mergePasswordData($data = [])
    {
        return array_merge([
            'current_password' => 'Password',
            'new_password' => 'Secret',
            'repeat_new_password' => 'Secret',
        ], $data);
    }
}
