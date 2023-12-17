<?php

namespace App\Http\Middleware;

use App\Models\Apartment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;

class ShareRoleView extends Middleware
{
    public function share(Request $request): array
    {
        if ($request->user() == null) {
            $role = null;
        } else {
            $role = Auth::user()->currentRole();
        }

        return [
            ...parent::share($request),
            'role' => $role
        ];
    }
}
