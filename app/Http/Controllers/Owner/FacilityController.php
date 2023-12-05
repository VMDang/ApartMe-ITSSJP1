<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Facility\StoreFacilityRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Facility;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::all();
        return Inertia::render("Owner/Facilities/ListFacility", ['facilities' => $facilities, ]);
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
    public function store(StoreFacilityRequest $request)
    {
        $request->validated();
        $facilities = new Facility([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'cost' => $request->get('cost'),
            'room_id' => $request->get('room_id'),
        ]);

        $facilities->save();
        return redirect()->route('facility.index');
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
    public function edit(Facility $facility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $facility_delete = Facility::find($id);
        $facility_delete->delete();
        return redirect()->route('facility.index');
    }
}
