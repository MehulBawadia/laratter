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
        return factory(User::class)->create($userData);
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
