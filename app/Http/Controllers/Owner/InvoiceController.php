<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInVoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        $invoices = Invoice::query()->select(['id', 'name', 'total', 'description', 'status', 'room_id', 'created_at', 'updated_at'])
            ->where('room_id', '=', Session::get('selectedRoomID'))
            ->get()->load('rooms');

        return Inertia::render('Owner/Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Owner/Invoices/Create');
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $request->validated();

        $invoice = new Invoice();
        $invoice->name = $request->input('name');
        $invoice->total = $request->input('total');
        $invoice->description = $request->input('description');
        $invoice->status = array_search($request->input('status'), config('app.invoice_status'));
        $invoice->room_id = Session::get('selectedRoomID');
        $invoice->save();

        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        return Inertia::render('Owner/Invoices/Show', [
            'invoice' => $invoice->load(['room']),
        ]);
    }

    public function edit(Invoice $invoice)
    {
        return Inertia::render('Owner/Invoices/Edit', [
            'invoice' => $invoice->load('room'),
        ]);
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $request->validated();

        $invoice->name = $request->input('name');
        $invoice->total = $request->input('total');
        $invoice->description = $request->input('description');
        $invoice->status = array_search($request->input('status'), config('app.invoice_status'));
        $invoice->room_id = Session::get('selectedRoomID');
        $invoice->save();
        return redirect()->route('invoices.index');

    }

    public function destroy(Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($invoice) {
                // Detach room
                $invoice->room()->update(['invoice_id' => null]);
                // Delete the invoice
                $invoice->delete();
            }, config('database.connections.mysql.attempts_transaction'));

            return redirect()->back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}

