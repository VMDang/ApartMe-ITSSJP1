<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestMail;
use App\Http\Requests\StoreMessageRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    /**
     * Display sent Request
     */
    public function indexSent()
    {
        $user = User::query()->find(Auth::id());
        $messages = DB::table("request_user")
            ->join("requests", "request_user.request_id", "=", "requests.id")
            ->where("request_user.user_id", "=", $user->id)
            ->select("requests.*")
            ->get();
        return Inertia::render('User/Message/SentMessage', ['user' => $user, 'messages' => $messages]);
    }

    /**
     * Display received Request
     */
    public function indexRecv()
    {
        return Inertia::render('User/Message/ReceivedMessage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $request->validated();
        $message = new RequestMail([
            'title' => $request->get('title'),
            'apartment_id' => $request->get('apartment_id'),
            'content' => $request->get('content'),
            'created_at' => now(),
        ]);

        $message->save();

        DB::table("request_user")->insert([
            'user_id' => $request->get('user_id'),
            'request_id' => $message->id,
            'is_owner' => $request->get('is_owner'),
        ]);

        return Redirect::route('messages.sent')->with('success', 'Account added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $message_delete = RequestMail::find($id);
        $message_delete->delete();
        $message_user = DB::table('request_user')->where('request_id', '=', $id);
        $message_user->delete();
        return Redirect::back()->with('success', 'Account deleted successfully');
    }
}
