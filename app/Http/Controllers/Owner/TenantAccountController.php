<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TenantAccountController extends Controller
{
    /**
     * Display the tenant's list account.
     */
    public function viewList(Request $request): Response
    {
        $tenants = User::all();
        // foreach($tenants as $tenant){
        //     echo $tenant->name;
        // }
        return Inertia::render('Owner/ListTenantAccount', ['tenants' => $tenants, ]);
    }

    /**
     * Display the form which use to create new tenant's account.
     */
    public function create(Request $request): Response
    {
        $data = $request->all();
        dd($data);

        return Inertia::response()->json(['message' => 'Data submitted successfully']);
    }

}