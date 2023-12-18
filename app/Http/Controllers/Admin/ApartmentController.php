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
    public function create(Request $request): Response
    {
        $apartments = Apartment::all();
        return Inertia::render('Admin/ApartmentList', ['apartments' => $apartments, ]);
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
