<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StoreTenantAccountRequest;

class TenantAccountController extends Controller
{
    /**
     * Display the tenant's list account.
     */
    public function create(Request $request): Response
    {
        $tenants = User::all();
        return Inertia::render('Owner/ListTenantAccount', ['tenants' => $tenants, ]);
    }

    /**
     * Add a new tenant account
     */
    public function store(StoreTenantAccountRequest $request)
    {
        $request->validated();
        $tenants = new User([
            'name' => $request->get('fullName'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'phone' => $request->get('phone'),
            'status' => $request->get('status'),
        ]);

        $tenants->save();
        return Redirect::route('tenant-accounts.view')->with('success', 'Account added successfully');
    }

    /**
     * Remove tenant account
     */
    public function destroy($id)
    {
        $tenant_delete = User::find($id);
        $tenant_delete->delete();
        return Redirect::back()->with('success', 'Account deleted successfully');
    }
}
