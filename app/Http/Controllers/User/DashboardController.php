<?php

namespace Laratter\Http\Controllers\User;

use Laratter\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the User dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.dashboard');
    }

    /**
     * Logout the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        return redirect(route('homePage'));
    }
}
