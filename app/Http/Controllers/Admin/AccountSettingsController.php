<?php

namespace Laratter\Http\Controllers\Admin;

use Laratter\Http\Controllers\Controller;
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

        return response()->json([
            'status' => 'success',
            'title' => 'Success !',
            'delay' => 3000,
            'message' => 'General Settings updated successfully.'
        ]);
    }
}
