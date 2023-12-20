<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = Room::query()->select(['id', 'name', 'floor', 'area', 'status', 'apartment_id'])
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->get()->load(['users', 'apartment']);

        $invoices = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->with(['invoices', 'users'])->get();

        $tenants = User::query()->whereHas('rooms', function ($query) use ($rooms) {
            $query->whereIn('room_id', $rooms->pluck('id'));
        })->get()->load('rooms');

        return Inertia::render('Dashboard', [
            'rooms' => $rooms,
            'invoices' => $invoices,
            'tenants' => $tenants,
        ]);
    }
}
