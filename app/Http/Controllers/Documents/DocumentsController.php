<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;


class DocumentsController extends Controller
{
    public function documents()
    {
        return Inertia::render('Documents/Documents');
    }




}