<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StoreTenantAccountRequest;

class RequestMessageController extends Controller
{
    public function received()
    {
        return Inertia::render('Requests/Received');
    }

    public function send()
    {
        return Inertia::render('Requests/Send');
    }


}