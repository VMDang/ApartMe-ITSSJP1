<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Facility\StoreFacilityRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\Facility;
use Inertia\Response;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $roomsWithFacilities = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->with('facilities')->get();

        return Inertia::render("Owner/Facilities/Index", [
            'rooms' => $roomsWithFacilities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $rooms = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))->get();
        return Inertia::render('Owner/Facilities/Create', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        $request->validated();

        $facilities = new Facility([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'cost' => $request->get('cost'),
            'room_id' => $request->get('room'),
        ]);

        $facilities->save();
        return redirect()->route('facilities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility): Response
    {
        $rooms = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))->get();

        return Inertia::render('Owner/Facilities/Edit', [
            'facility' => $facility->load('room'),
            'rooms' => $rooms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFacilityRequest $request, Facility $facility): RedirectResponse
    {
        $request->validated();

        $facility->name = $request->input('name');
        $facility->room_id = $request->input('room');
        $facility->cost = $request->input('cost');
        $facility->description = $request->input('description');
        $facility->save();

        return redirect()->route('facilities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        try {
            DB::transaction(function () use ($facility) {
                $facility->delete();
            }, config('database.connections.mysql.attempts_transaction'));

            return redirect()->back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
