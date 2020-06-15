<?php

namespace Tests\Feature;

use Laratter\User;
use Tests\TestCase;
use Laratter\Mail\AdminGenerated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateAdministratorTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->getRoute = route('admin.generate');
        $this->postRoute = route('admin.generate.store');
    }

    /** @test */
    public function there_are_no_users_in_the_database()
    {
        $this->assertEmpty(User::all());
    }

    /** @test */
    public function guest_is_redirected_if_url_is_incomplete()
    {
        $this->get('/admin')->assertRedirect(route('admin.generate'));
    }

    /** @test */
    public function guest_sees_generate_administrator_section()
    {
        $this->get($this->getRoute)
            ->assertViewIs('admin.generate')
            ->assertSee('Generate Administrator')
            ->assertOk();
    }

    /** @test */
    public function guest_becomes_administrator()
    {
        $this->assertEmpty(User::all());

        $this->postJson($this->postRoute, $this->mergeData())
            ->assertJson([
                'status' => 'success',
                'title' => 'Success !',
                'message' => 'Administrator generated successfully.',
                'redirectTo' => route('admin.dashboard')
            ]);

        $this->assertNotEmpty(User::all());
    }

    /** @test */
    public function guest_is_logged_in_after_becoming_administrator()
    {
        $this->assertEmpty(User::all());

        $this->assertFalse(auth()->check());

        $this->postJson($this->postRoute, $this->mergeData())
            ->assertJson([
                'redirectTo' => route('admin.dashboard')
            ]);

        $this->assertTrue(auth()->check());

        $this->assertNotEmpty(User::all());
    }

    /** @test */
    public function shoots_mail_to_the_given_email_address_on_becoming_an_administrator()
    {
        Mail::fake();

        $this->postJson($this->postRoute, $this->mergeData());

        Mail::assertSent(AdminGenerated::class, function ($mail) {
            return $mail->hasTo(User::first()->email);
        });
    }

    /** @test */
    public function first_name_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeData(['first_name' => '']))
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
        $this->post($this->postRoute, $this->mergeData(['first_name' => $this->faker->paragraphs(10, true)]))
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
        $this->post($this->postRoute, $this->mergeData(['last_name' => '']))
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
        $this->post($this->postRoute, $this->mergeData(['last_name' => $this->faker->paragraphs(10, true)]))
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
        $this->post($this->postRoute, $this->mergeData(['username' => '']))
            ->assertSessionHasErrors(['username' => 'The username field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username field is required.'
        );
    }

    /** @test */
    public function username_field_may_contain_only_letters_numbers_dashes_and_underscores()
    {
        $this->post($this->postRoute, $this->mergeData(['username' => 'HF:@#$*/']))
            ->assertSessionHasErrors(['username' => 'The username may only contain letters, numbers, dashes and underscores.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    public function username_may_not_be_greater_than_50_characters()
    {
        $username = str_replace(
            [' ', '!','@','$','%','^','&','*','(',')','#','_','-','.','\''],
            '_',
            $this->faker->unique()->words(50, true)
        );

        $this->post($this->postRoute, $this->mergeData(['username' => $username]))
            ->assertSessionHasErrors(['username' => 'The username may not be greater than 50 characters.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may not be greater than 50 characters.'
        );
    }

    /** @test */
    public function email_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeData(['email' => '']))
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
        $this->post($this->postRoute, $this->mergeData(['email' => 'maddy@@521.rhdtfgjvhb']))
            ->assertSessionHasErrors(['email' => 'The email must be a valid email address.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    public function repeat_email_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeData(['repeat_email' => '']))
            ->assertSessionHasErrors(['repeat_email' => 'The repeat email field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_email'),
            'The repeat email field is required.'
        );
    }

    /** @test */
    public function repeat_email_must_be_a_valid_email_address()
    {
        $this->post($this->postRoute, $this->mergeData(['repeat_email' => 'maddy@@521.rhdtfgjvhb']))
            ->assertSessionHasErrors(['repeat_email' => 'The repeat email must be a valid email address.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_email'),
            'The repeat email must be a valid email address.'
        );
    }

    /** @test */
    public function repeat_email_must_be_a_same_as_email()
    {
        $this->post($this->postRoute, $this->mergeData(['repeat_email' => $this->faker->unique()->email]))
            ->assertSessionHasErrors(['repeat_email' => 'The repeat email and email must match.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_email'),
            'The repeat email and email must match.'
        );
    }

    /** @test */
    public function password_field_is_required()
    {
        $this->post($this->postRoute, $this->mergeData(['password' => '']))
            ->assertSessionHasErrors(['password' => 'The password field is required.']);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }

    /**
     * Merge the default data with the given data.
     *
     * @param  array $attributes
     * @return array
     */
    protected function mergeData($attributes = [])
    {
        $username = str_replace(
            ['!','@','$','%','^','&','*','(',')','#','_','-','.','\''],
            '_',
            $this->faker->unique()->userName
        );

        return array_merge([
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'username' => $username,
            'email' => $email = $this->faker->unique()->email,
            'repeat_email' => $email,
            'password' => 'Password',
        ], $attributes);
    }
}
