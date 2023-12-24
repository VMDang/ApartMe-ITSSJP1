<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = Registration::query()->with('apartmentType')
            ->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Registrations/Index', [
            'registrations' => $registrations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        return Inertia::render('Admin/Registrations/Show', [
            'registration' => $registration->load('apartmentType'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'approve_status' => ['required', Rule::in(array_values(config('app.registration_approve_status')))],
            'approve_reason' => ['max:255'],
        ]) ;

        $registration->approve_status = array_search(
            $validated['approve_status'],
            config('app.registration_approve_status')
        );
        $registration->approve_reason = $validated['approve_reason'];
        $registration->save();

        if ($validated['approve_status'] == 'Accept') {
            DB::transaction(
                function () use ($registration) {
                    Apartment::query()->create([
                        'address' => $registration->address,
                        'owner_email' => $registration->owner_email,
                        'number_floors' => $registration->number_floors,
                        'number_rooms' => $registration->number_rooms,
                        'area' => $registration->area,
                        'apartment_type_id' => $registration->apartment_type_id,
                        'created_at' => Carbon::now(),
                    ]);

                    User::query()->updateOrInsert(
                        [
                            'email' => $registration->owner_email,
                        ],
                        [
                            'name' => $registration->name,
                            'phone' => $registration->phone,
                            'password' => $registration->password,
                            'status' => array_search('Active', config('app.user_status')),
                            'created_at' => Carbon::now(),
                        ]
                    );

                    $user = User::query()->where('email', '=', $registration->owner_email)->first();

                    DB::table('room_user')->insert([
                        'user_id' => $user->id,
                        'role_id' => Role::query()->where('name', '=', 'OWNER')->first()->id,
                    ]);
                },
                config('database.connections.mysql.attempts_transaction')
            );
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        try {
            DB::transaction(
                function () use ($registration) {
                    $registration->delete();
                },
                config('database.connections.mysql.attempts_transaction')
            );

            return redirect()->back();
        } catch (\Exception $exception) {
            $exception->getMessage();
        }
    }
}
