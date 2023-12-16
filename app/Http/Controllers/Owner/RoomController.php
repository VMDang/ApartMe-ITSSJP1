<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $rooms = Room::query()->select(['id', 'name', 'floor', 'area', 'status', 'apartment_id'])
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->get()->load(['users', 'apartment']);
        return Inertia::render('Owner/Rooms/IndexNew', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Owner/Rooms/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $request->validated();

        $room = new Room();
        $room->name = $request->input('name');
        $room->floor = $request->input('floor');
        $room->area = $request->input('area');
        $room->status = array_search($request->input('status'), config('app.room_status'));
        $room->apartment_id = Session::get('selectedApartmentID');
        $room->save();

        return redirect()->route('rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return Inertia::render('Owner/Rooms/Show', [
            'room' => $room->load(['users', 'apartment']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return Inertia::render('Owner/Rooms/Edit', [
            'room' => $room->load('users'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRoomRequest $request, Room $room): RedirectResponse
    {
        $request->validated();

        $room->name = $request->input('name');
        $room->floor = $request->input('floor');
        $room->area = $request->input('area');
        $room->status = array_search($request->input('status'), config('app.room_status'));
        $room->save();

        return redirect()->route('rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            DB::transaction(
                function () use ($room) {
                    foreach ($room->users as $user) {
                        $countRecord = DB::table('room_user')->where('user_id', '=', $user->id)->count();
                        $room->users()->detach($user->id);
                        if ($countRecord == 1) {
                            User::query()->find($user->id)->delete();
                        }
                    }
                    $room->facilities()->delete();
                    $room->invoices()->delete();
                    $room->delete();
                },
                config('database.connections.mysql.attempts_transaction')
            );

            return redirect()->back();
        } catch (\Exception $exception) {
            $exception->getMessage();
        }
    }
}
