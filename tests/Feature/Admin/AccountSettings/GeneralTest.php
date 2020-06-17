<?php

namespace Tests\Feature\Admin\AccountSettings;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneralTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loginAdministrator();

        $this->getRoute = route('admin.accountSettings');
        $this->postRoute = route('admin.accountSettings.updateGeneral');
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
            ->assertSee('Account Settings');
    }

    /** @test */
    public function administrator_can_update_their_general_settings()
    {
        $this->assertEquals(auth()->user()->username, 'admin');

        $this->patch($this->postRoute, $this->mergeGeneralData(['username' => 'john']))
            ->assertJson([
                'status' => 'success',
                'title' => 'Success !',
                'delay' => 3000,
                'message' => 'General Settings updated successfully.'
            ]);

        $this->assertEquals(auth()->user()->username, 'john');
    }

    /** @test */
    public function first_name_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['first_name' => '']))
            ->assertSessionHasErrors(['first_name' => 'The first name field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name field is required.'
        );
    }

    /** @test */
    public function first_name_may_not_be_greater_than_255_characters()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['first_name' => $this->faker->paragraphs(10, true)]))
            ->assertSessionHasErrors(['first_name' => 'The first name may not be greater than 255 characters.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name may not be greater than 255 characters.'
        );
    }

    /** @test */
    public function last_name_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['last_name' => '']))
            ->assertSessionHasErrors(['last_name' => 'The last name field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name field is required.'
        );
    }

    /** @test */
    public function last_name_may_not_be_greater_than_255_characters()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['last_name' => $this->faker->paragraphs(10, true)]))
            ->assertSessionHasErrors(['last_name' => 'The last name may not be greater than 255 characters.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name may not be greater than 255 characters.'
        );
    }

    /** @test */
    public function username_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['username' => '']))
            ->assertSessionHasErrors(['username' => 'The username field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username field is required.'
        );
    }

    /** @test */
    public function username_may_not_be_greater_than_50_characters()
    {
        $username = str_replace(' ', '_', $this->faker->words(100, true));
        $this->patch($this->postRoute, $this->mergeGeneralData(['username' => $username]))
            ->assertSessionHasErrors(['username' => 'The username may not be greater than 50 characters.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may not be greater than 50 characters.'
        );
    }

    /** @test */
    public function username_must_be_unique()
    {
        factory(\Laratter\User::class)->create([
            'username' => 'johnny',
            'email' => 'johnny@example.com'
        ]);

        $this->patch($this->postRoute, $this->mergeGeneralData(['username' => 'johnny']))
            ->assertSessionHasErrors(['username' => 'The username has already been taken.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username has already been taken.'
        );
    }

    /** @test */
    public function username_shoud_include_only_letters_numbers_dashes_and_underscores()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['username' => 'jo%^hnny!@-*/']))
            ->assertSessionHasErrors(['username' => 'The username may only contain letters, numbers, dashes and underscores.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    public function email_field_is_required()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['email' => '']))
            ->assertSessionHasErrors(['email' => 'The email field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email field is required.'
        );
    }

    /** @test */
    public function email_must_be_a_valid_email_address()
    {
        $this->patch($this->postRoute, $this->mergeGeneralData(['email' => '@some-random@exmple...com']))
            ->assertSessionHasErrors(['email' => 'The email must be a valid email address.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    public function email_must_be_unique()
    {
        factory(\Laratter\User::class)->create([
            'username' => 'johnny',
            'email' => 'johnny@example.com'
        ]);

        $this->patch($this->postRoute, $this->mergeGeneralData(['email' => 'johnny@example.com']))
            ->assertSessionHasErrors(['email' => 'The email has already been taken.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email has already been taken.'
        );
    }

    /**
     * Merge the default data with dummy data.
     *
     * @param  array  $data
     * @return array
     */
    protected function mergeGeneralData($data = [])
    {
        return array_merge([
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
        ], $data);
    }
}
