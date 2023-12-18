<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Apartment;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $userLogged = User::query()->find(Auth::id());

        if (count($userLogged->roles) == 1) {
            $roleName = $userLogged->roles->first()->name;

            if ($roleName == 'ADMIN') {
                return redirect()->route('dashboard');      // Go to Admin page registration
            } elseif ($roleName == "OWNER") {
                Session::put(
                    'selectedApartmentID',
                    DB::table('apartments')->where('owner_email', '=', $userLogged->email)->first()->id
                );

                return redirect()->route('dashboard');      // Go to Owner dashboard
            } elseif ($roleName == "TENANT") {
                $apartmentID = Room::query()->find(
                    DB::table('room_user')->where('user_id', '=', $userLogged->id)->first()->room_id
                )->apartment->id;
                Session::put('selectedApartmentID', $apartmentID);

                return redirect()->route('rooms.index');
            }
        } elseif (count($userLogged->roles) > 1) {
            return redirect()->route('selectApartment.show');
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function selectApartmentShow(): Response
    {
        return Inertia::render('SelectApartment', [
            'owner' => $this->getApartmentOwner(),
            'tenants' => $this->getApartmentsTenant(),
        ]);
    }

    public function selectApartmentStore(Apartment $apartment): RedirectResponse
    {
        $apartmentOwner = $this->getApartmentOwner();
        $apartmentsTenant = $this->getApartmentsTenant();

        if (isset($apartmentOwner) && $apartmentOwner->id == $apartment->id) {
            // User is logging select a apartment role owner
            Session::put('selectedApartmentID', $apartment->id);

            return redirect()->route('dashboard');      // Go to Owner dashboard
        }

        if (!empty($apartmentsTenant)) {
            foreach ($apartmentsTenant as $value) {
                if ($value->id == $apartment->id) {
                    // User is logging select a apartment role tenant
                    Session::put('selectedApartmentID', $apartment->id);

                    return redirect()->route('rooms.index');
                }
            }
        }

        return redirect()->route('selectApartment.show');
    }

    private function getApartmentOwner(): Apartment|null
    {
        return Apartment::query()->where('owner_email', '=', Auth::user()->email)->first();
    }

    private function getApartmentsTenant(): array|null
    {
        $roomsID = DB::table('room_user')->where([
            ['user_id', '=', Auth::id()],
            ['role_id', '=', Role::query()->select('id')->where('name', '=', 'TENANT')->first()->id],
        ])->get('room_id');

        $tenants = [];
        foreach ($roomsID as $roomID) {
            $tenants[] = Room::query()->find($roomID->room_id)->apartment;
        }
        $tenants = collect($tenants)->unique('id')->values()->all();

        return $tenants;
    }
}
