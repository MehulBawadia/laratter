<?php

namespace Laratter\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Laratter\Http\Controllers\Controller;
use Laratter\Http\Requests\ChangePasswordRequest;
use Laratter\Http\Requests\AccountGeneralSettingsRequest;

class AccountSettingsController extends Controller
{
    /**
     * Display the account settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $admin = auth()->user();

        return view('admin.account-settings', compact('admin'));
    }

    /**
     * Update the general details of the authenticated user.
     *
     * @param  \Laratter\Http\Requests\AccountGeneralSettingsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function udpateGeneral(AccountGeneralSettingsRequest $request)
    {
        auth()->user()->update($request->all());

        return $this->successResponse('General Settings updated successfully.');
    }

    /**
     * Change the password of the authenticated user.
     *
     * @param  \Laratter\Http\Requests\ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return $this->failedResponse('Invalid current password.');
        }

        auth()->user()->update(['password' => bcrypt($request->new_password)]);

        return $this->successResponse('Password updated successfully.');
    }
}
