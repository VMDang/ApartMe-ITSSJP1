<?php

namespace App\Http\Middleware;

use App\Models\Apartment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnerAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apartmentSelected = Apartment::query()->find(Session::get('selectedApartmentID'));
        $hasRoleOwner = User::query()->find(Auth::user()->getAuthIdentifier())->roles->contains('name', 'OWNER');

        if (isset($apartmentSelected) && (Auth::user()->email == $apartmentSelected->owner_email) && $hasRoleOwner) {
            return $next($request);
        } else {
            return abort(401);
        }
    }
}
