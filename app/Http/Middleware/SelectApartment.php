<?php

namespace App\Http\Middleware;

use App\Models\Apartment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SelectApartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = User::query()->find(Auth::user()->getAuthIdentifier())->roles->first();
        if ($role->name == "ADMIN") {
            return $next($request);
        }

        if (Session::get('selectedApartmentID') == null) {
            return redirect()->route('selectApartment.show');
        }

        return $next($request);
    }
}
