<?php

namespace Laratter\Http\Controllers;

use Laratter\User;
use Illuminate\Http\Request;
use Laratter\Mail\AdminGenerated;
use Illuminate\Support\Facades\Mail;

class GenerateAdministratorController extends Controller
{
    /**
     * Display the form to generate the administrator.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (User::first()) {
            return redirect(route('admin.login'));
        }

        return view('admin.generate');
    }

    /**
     * Store the Administrator details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => 'required|alpha_dash|max:50',
            'email' => 'required|email',
            'repeat_email' => 'required|email|same:email',
            'password' => 'required',
        ]);

        session(['password' => $request->password]);

        $request['code'] = (new User)->generateCode();
        $request['password'] = bcrypt($request->password);
        $user = User::create($request->all());

        Mail::to($user->email)->send(new AdminGenerated);

        auth()->login($user);

        return $this->successResponse('Administrator generated successfully. Redirecting...', [
            'redirectTo' => auth()->user()->getDashboard()
        ]);
    }

    /**
     * Redirect the guest user to the generate admin page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toGeneratePage()
    {
        return redirect(route('admin.generate'));
    }
}
