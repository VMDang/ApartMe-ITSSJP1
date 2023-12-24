<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentRole = Auth::user()->currentRole();
        $roomsWithInvoices = Room::query()->where('apartment_id', '=', Session::get('selectedApartmentID'))
            ->with(['invoices', 'users'])->get();
        if ($currentRole == 'OWNER') {
            $i = 0;
            foreach ($roomsWithInvoices as $room) {
                foreach ($room->invoices as $invoice) {
                    $paymentTmp = Payment::query()->where('invoice_id', '=', $invoice->id)->first();
                    if (isset($paymentTmp)) {
                        $paymentsOwner[$i]['payment_info'] = $paymentTmp;
                        $paymentsOwner[$i]['payment_by'] = User::query()->find($paymentTmp->payment_by);
                        $paymentsOwner[$i]['invoice'] = Invoice::query()->find($paymentTmp->invoice_id)->load('room');
                        $i++;
                    }
                }
            }
            if (empty($paymentsOwner)) {
                $paymentsOwner = [];
            }
            return Inertia::render('User/Payments/Index', [
                'payments' => $paymentsOwner,
            ]);
        } elseif ($currentRole == 'TENANT') {
            foreach ($roomsWithInvoices as $room) {
                foreach ($room->users as $user) {
                    if ($user->pivot->user_id == Auth::id()) {
                        $roomsTenantLogged[] = $room;
                    }
                }
            }

            $i = 0;
            foreach ($roomsTenantLogged as $room) {
                foreach ($room->invoices as $invoice) {
                    $paymentTmp = Payment::query()->where('invoice_id', '=', $invoice->id)->first();
                    if (isset($paymentTmp)) {
                        $paymentsTenant[$i]['payment_info'] = $paymentTmp;
                        $paymentsTenant[$i]['payment_by'] = User::query()->find($paymentTmp->payment_by);
                        $paymentsTenant[$i]['invoice'] = Invoice::query()->find($paymentTmp->invoice_id)->load('room');
                        $i++;
                    }
                }
            }
            if (empty($paymentsTenant)) {
                $paymentsTenant = [];
            }

            return Inertia::render('User/Payments/Index', [
                'payments' => $paymentsTenant,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice): Response
    {
        return Inertia::render('User/Payments/Create', [
            'invoice' => $invoice->load(['room', 'payment']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, Invoice $invoice)
    {
        $request->validated();
        try {
            DB::transaction(function () use ($request, $invoice) {
                Payment::query()->updateOrInsert(
                    ['invoice_id' => $invoice->id],
                    [
                        'payment_method' => $request->input('payment_method'),
                        'total' => $request->input('total'),
                        'message' => $request->input('message'),
                        'status' => array_search("Paid", config('app.payment_status')),
                        'payment_by' => Auth::id(),
                    ]
                );
            });

            return redirect()->back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
    public function approve(Invoice $invoice): RedirectResponse
    {
        $invoice->status = array_search('Done', config('app.invoice_status'));
        $invoice->save();

        return redirect()->back();
    }
}
