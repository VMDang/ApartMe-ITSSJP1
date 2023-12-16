<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StoreTenantAccountRequest;
use function Termwind\render;

class TenantAccountController extends Controller
{
    /**
     * Display the tenant's list account.
     */
    public function index(Request $request): Response
    {
        $rooms = Room::query()->select('id')
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->get();

        $tenants = User::query()->whereHas('rooms', function ($query) use ($rooms) {
            $query->whereIn('room_id', $rooms->pluck('id'));
        })->get()->load('rooms');

        return Inertia::render('Owner/Tenants/IndexNew', ['tenants' => $tenants, ]);
    }

    public function create(): Response
    {
        $rooms = Room::query()
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->where('status', '=', array_search("Active", config('app.room_status')))
            ->get();
        return Inertia::render('Owner/Tenants/Create', [ 'rooms' => $rooms ]);
    }

    /**
     * Add a new tenant account
     */
    public function store(StoreTenantAccountRequest $request)
    {
        $request->validated();

        $checkUserExists = User::query()->where('email', $request->get('email'))->first();
        $role_id = Role::query()->where('name', '=', 'TENANT')->first()->id;
        $rooms = $request->get('rooms');

        if ($checkUserExists) {
            $existingRooms = DB::table('room_user')
                ->where('user_id', $checkUserExists->id)
                ->where('role_id', $role_id)
                ->whereIn('room_id', $rooms)
                ->pluck('room_id')
                ->toArray();

            $filteredRooms = collect($rooms)->reject(function ($room) use ($existingRooms) {
                return in_array($room, $existingRooms);
            })->toArray();

            DB::transaction(function () use ($checkUserExists, $filteredRooms, $role_id) {
                $checkUserExists->rooms()->attach($filteredRooms, ['role_id' => $role_id]);
            });
        } else {
            $tenant = new User([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'phone' => $request->get('phone'),
                'status' => array_search("Active", config('app.user_status')),
            ]);
            $tenant->save();

            DB::transaction(function () use ($tenant, $rooms, $role_id) {
                $tenant->rooms()->attach($rooms, ['role_id' => $role_id]);
            });
        }

        return Redirect::route('tenant-accounts.index')->with('success', 'Account added successfully');
    }

    /**
     * Remove tenant account
     */
    public function destroy(User $user)
    {
        try {
            DB::transaction(
                function () use ($user) {
                    foreach ($user->rooms as $room) {
                        $user->rooms()->detach($room->id);
                    }
                    foreach ($user->documents as $document) {
                        $user->documents()->detach($document->id);
                    }
                    foreach ($user->requests as $request) {
                        $user->requests()->detach($request->id);
                    }
                    $user->delete();
                },
                config('database.connections.mysql.attempts_transaction')
            );
            return Redirect::back()->with('success', 'Account deleted successfully');
        } catch (\Exception $exception) {
            $exception->getMessage();
        }
    }
}
