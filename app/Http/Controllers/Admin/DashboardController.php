<?php

namespace Laratter\Http\Controllers\Admin;

use Laratter\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the Administrator dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Logout the administrator.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        return redirect(route('homePage'));
    }
}
