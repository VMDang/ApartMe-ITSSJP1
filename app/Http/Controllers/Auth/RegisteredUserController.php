<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\ApartmentType;
use App\Models\Registration;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', ['apartment_types' => ApartmentType::all()]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRegistrationRequest $request)
    {
        $request->validated();

        $registration = Registration::create([
            'name' => $request->name,
            'owner_email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'area' => $request->area,
            'number_floors' => $request->number_floors,
            'number_rooms' => $request->number_rooms,
            'apartment_type_id' => $request->apartment_type_id,
            'created_at' => now(),
        ]);

        return redirect()->route('login');
    }
}
