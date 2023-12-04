<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    public function create(): Response
    {
        $registration = Registration::all();
        return Inertia::render('Admin/RegistrationList', ['registration' => $registration]);
    }
    public function  show(): Response{
        return Inertia::render('');
    }
    public function Accept(Request $request): RedirectResponse
    {
        $id = $request->input('id');

        if ($id) {
            $registration = Registration::find($id);

            if ($registration) {
                // Cập nhật trạng thái phê duyệt trong cơ sở dữ liệu
                $registration->approve_status = '1';
                $registration->save();

                return redirect()->back()->with('success', 'Request approved successfully');
            } else {
                return redirect()->back()->with('error', 'Registration not found');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid request');
        }
    }

        public function delete(Request $request): RedirectResponse
    {
        $id = $request->input('id');

        if ($id) {
            $registration = Registration::find($id);

            if ($registration) {
                $registration->delete();

                return redirect()->back()->with('success', 'Registration deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Registration not found');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid request');
        }
    }

}
