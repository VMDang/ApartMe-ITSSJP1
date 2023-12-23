<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\RequestMail;
use App\Http\Requests\StoreRequestRequest;
use App\Models\User;
use App\Models\Apartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RequestController extends Controller
{
    /**
     * Display sent Request
     */
    public function indexSent()
    {
        $requests = RequestMail::query()
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->whereHas('users', function ($query) {
                $query->where('user_id', '=', Auth::id());
                $query->where('is_owner', '=', true);
            })->with('users', function ($query) {
                $query->where('user_id', '!=', Auth::id());
            })->orderBy('created_at', 'desc')->get();

        return Inertia::render('User/Requests/SentRequests', [
            'requests' => $requests
        ]);
    }

    /**
     * Display received Request
     */
    public function indexRecv()
    {
        $requests = RequestMail::query()
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->whereHas('users', function ($query) {
                $query->where('user_id', '=', Auth::id());
                $query->where('is_owner', '=', false);
            })->with('users', function ($query) {
                $query->where('is_owner', '=', true);
            })->orderBy('created_at', 'desc')->get();

        return Inertia::render('User/Requests/ReceivedRequests', [
            'requests' => $requests,
        ]);
    }

    public function create(): Response
    {
        $rooms = Room::query()->select('id')
            ->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->get();

        $apartmentSelected = Apartment::query()->find(Session::get('selectedApartmentID'));
        $owner = User::query()->where('email', '=', $apartmentSelected->owner_email)
            ->get()->toArray();

        $tenants = User::query()->whereHas('rooms', function ($query) use ($rooms) {
            $query->whereIn('room_id', $rooms->pluck('id'));
        })->get()->toArray();
        $usersApartment = array_merge($owner, $tenants);

        foreach ($usersApartment as $u) {
            if (!($u['id'] == Auth::id())) {
                $users[] = $u;
            }
        }
        return Inertia::render('User/Requests/Create', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestRequest $request): RedirectResponse
    {
        $request->validated();

        DB::transaction(
            function () use ($request) {
                $newRequest = new RequestMail([
                    'title' => $request->get('title'),
                    'apartment_id' => Session::get('selectedApartmentID'),
                    'content' => $request->get('content'),
                ]);

                $newRequest->save();

                $newRequest->users()->attach(Auth::id(), ['is_owner' => true]);
                $newRequest->users()->attach($request->get('receivers'), ['is_owner' => false]);
            },
            config('database.connections.mysql.attempts_transaction')
        );

        return Redirect::route('requests.sent')->with('success', 'Account added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $requests_delete = RequestMail::query()->find($id);
        if ($requests_delete->exists()) {
            DB::transaction(
                function () use ($requests_delete) {
                    $requests_delete->users()->detach();
                    $requests_delete->delete();
                },
                config('database.connections.mysql.attempts_transaction')
            );
        }

        return Redirect::back()->with('success', 'Account deleted successfully');
    }
}
