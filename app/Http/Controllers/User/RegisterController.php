<?php

namespace Laratter\Http\Controllers\User;

use Laratter\User;
use Illuminate\Http\Request;
use Laratter\Mail\UserRegistered;
use Illuminate\Support\Facades\Mail;
use Laratter\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('user.dashboard'));
        }

        return view('user.register');
    }

    /**
     * Store the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => 'required|unique:users,username|alpha_dash|max:50',
            'email' => 'required|email|unique:users,email',
            'repeat_email' => 'required|email|same:email',
            'password' => 'required',
        ]);

        session(['password' => $request->password]);

        $request['code'] = (new User)->generateCode();
        $request['password'] = bcrypt($request->password);
        $user = User::create($request->all());

        Mail::to($user->email)->send(new UserRegistered($user));

        auth()->login($user);

        return $this->successResponse('User registered successfully. Redirecting...', [
            'redirectTo' => auth()->user()->getDashboard()
        ]);
    }
}
