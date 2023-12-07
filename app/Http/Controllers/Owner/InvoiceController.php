<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        $roomsWithInvoices = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->with('invoices')->get();

        return Inertia::render('Owner/Invoices/Index', [
            'rooms' => $roomsWithInvoices,
        ]);
    }

    public function create(): Response
    {
        $rooms = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))->get();
        return Inertia::render('Owner/Invoices/Create', [
                'rooms' => $rooms,
            ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $request->validated();
        $carbonDate = Carbon::parse($request->input('deadline'))->addDay();

        $invoice = new Invoice();
        $invoice->name = $request->input('name');
        $invoice->total = $request->input('total');
        $invoice->description = $request->input('description');
        $invoice->status = array_search('Not Done', config('app.invoice_status'));
        $invoice->deadline = $carbonDate->format('Y-m-d');
        $invoice->room_id = $request->input('room');
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
        $rooms = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))->get();
        return Inertia::render('Owner/Invoices/Edit', [
            'invoice' => $invoice->load('room'),
            'rooms' => $rooms,
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $request->validated();
        $carbonDate = Carbon::parse($request->input('deadline'));

        $invoice->name = $request->input('name');
        $invoice->total = $request->input('total');
        $invoice->description = $request->input('description');
        $invoice->deadline = $carbonDate->format('Y-m-d');
        $invoice->room_id = $request->input('room');
        $invoice->status = array_search($request->input('status'), config('app.invoice_status'));
        $invoice->save();

        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($invoice) {
                $invoice->payment()->delete();
                $invoice->delete();
            }, config('database.connections.mysql.attempts_transaction'));

            return redirect()->back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
