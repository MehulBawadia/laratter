<?php

namespace Tests;

use Laratter\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Generate the fake Administrator for testing.
     *
     * @param  array  $userData
     * @return \Laratter\User
     */
    public function generateAdministrator($userData = [])
    {
        $attributes = array_merge([
            'username' => 'admin',
            'email' => 'admin@example.com',
        ], $userData);

        return factory(User::class)->create($attributes);
    }

    /**
     * Login the Administrator for testing.
     *
     * @param  array  $userData
     * @return void
     */
    public function loginAdministrator($userData = [])
    {
        return auth()->login(
            $this->generateAdministrator($userData)
        );
    }
}
