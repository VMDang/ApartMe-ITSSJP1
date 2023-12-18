<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestMail;
use App\Http\Requests\StoreRequestRequest;
use App\Models\User;
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
        $user = User::query()->find(Auth::id());
        $requests = DB::table("request_user")
            ->join("requests", "request_user.request_id", "=", "requests.id")
            ->where("request_user.user_id", "=", $user->id)
            ->select("requests.*")
            ->distinct()
            ->get();
        return Inertia::render('User/Requests/SentRequests', ['user' => $user, 'requests' => $requests]);
    }

    /**
     * Display received Request
     */
    public function indexRecv()
    {
        return Inertia::render('User/Requests/ReceivedRequests');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestRequest $request)
    {
        $request->validated();
        $requests = new RequestMail([
            'title' => $request->get('title'),
            'apartment_id' => $request->get('apartment_id'),
            'content' => $request->get('content'),
            'created_at' => now(),
        ]);

        $requests->save();

        DB::table("request_user")->insert([
            'user_id' => $request->get('user_id'),
            'request_id' => $requests->id,
            'is_owner' => $request->get('is_owner'),
        ]);

        return Redirect::route('requests.sent')->with('success', 'Account added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $requests_delete = RequestMail::find($id);
        $requests_delete->delete();
        $requests_user = DB::table('request_user')->where('request_id', '=', $id);
        $requests_user->delete();
        return Redirect::back()->with('success', 'Account deleted successfully');
    }
}
