<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class ApartmentController extends Controller
{
    /**
     * Display the arpartment list account.
     */
    public function index(): Response
    {
        $apartments = Apartment::all();
        $i = 0;
        foreach ($apartments as $apartment) {
            $owner = User::query()->where('email', '=', $apartment->owner_email)->first();
            $apartments[$i]['owner'] = $owner;
            $apartments[$i]['apartment_type'] = $apartment->load('apartmentType');
            $i++;
        }
        return Inertia::render('Admin/Apartments/Index', [
            'apartments' => $apartments,
        ]);
    }

    public function show(Apartment $apartment)
    {
        if (empty($apartment->id)) {
            $apartment = Apartment::query()->find(Session::get('selectedApartmentID'));
        }

        $owner = User::query()->where('email', '=', $apartment->owner_email)->first();
        return Inertia::render("User/Apartments/Show", [
            'apartment' => $apartment->load('apartmentType'),
            'owner' => $owner,
        ]);
    }
}
