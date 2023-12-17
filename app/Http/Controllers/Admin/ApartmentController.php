<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
}
