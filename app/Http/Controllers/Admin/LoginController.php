<?php

namespace Laratter\Http\Controllers\Admin;

use Laratter\Http\Controllers\Controller;
use Laratter\Http\Requests\LoginFormRequest;

class LoginController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('admin.dashboard'));
        }

        return view('admin.login');
    }

    /**
     * Check the credentials and login the administrator.
     *
     * @param  \Laratter\Http\Requests\LoginFormRequest $request
     * @return \Response
     */
    public function check(LoginFormRequest $request)
    {
        $loggedIn = $this->processCredentials($request);

        if ($loggedIn) {
            return $this->successResponse('Logged in successfully. Redirecting...', [
                'redirectTo' => auth()->user()->getDashboard()
            ]);
        }

        return $this->failedResponse('Invalid Credentials.');
    }

    /**
     * Match the login credentials with that in the database table.
     *
     * @param  \Laratter\Http\Requests\LoginFormRequest $request
     * @return boolean
     */
    public function processCredentials($request)
    {
        $field = filter_var($request->usernameOrEmail, FILTER_VALIDATE_EMAIL)
                    ? 'email'
                    : 'username';

        $request->merge([$field => $request->usernameOrEmail]);

        if (auth()->attempt($request->only($field, 'password'))) {
            return true;
        }

        return false;
    }
}
