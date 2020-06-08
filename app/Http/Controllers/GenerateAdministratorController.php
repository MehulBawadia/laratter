<?php

namespace Laratter\Http\Controllers;

use Laratter\User;
use Illuminate\Http\Request;

class GenerateAdministratorController extends Controller
{
    /**
     * Display the form to generate the administrator.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
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

        $request['code'] = (new User)->generateCode();
        $request['password'] = bcrypt($request->password);
        User::create($request->all());

        return response()->json([
            'status' => 'success',
            'title' => 'Success !',
            'message' => 'Administrator generated successfully.',
            'redirectTo' => route('homePage')
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