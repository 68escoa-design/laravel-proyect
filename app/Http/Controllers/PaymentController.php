<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('client.user')->get();
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payment::with('client.user')->findOrFail($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create([
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method ?? 'cash',
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Pago registrado exitosamente', 'payment' => $payment->load('client.user')], 201);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($request->has('amount')) {
            $payment->amount = $request->amount;
        }
        if ($request->has('payment_method')) {
            $payment->payment_method = $request->payment_method;
        }
        if ($request->has('reference')) {
            $payment->reference = $request->reference;
        }
        if ($request->has('notes')) {
            $payment->notes = $request->notes;
        }
        $payment->save();

        return response()->json(['message' => 'Pago actualizado exitosamente', 'payment' => $payment->load('client.user')]);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Pago eliminado exitosamente']);
    }

    public function clientPayments($clientId)
    {
        $client = Client::findOrFail($clientId);
        $payments = $client->payments;
        return response()->json($payments);
    }

    public function clientAccountStatus($clientId)
    {
        $client = Client::with('payments', 'plans.plan')->findOrFail($clientId);
        
        $totalPaid = $client->payments->sum('amount');
        $monthlyPayment = $client->plans->where('active', true)->first()?->plan->price ?? 0;
        $balance = $monthlyPayment - $totalPaid;
        
        return response()->json([
            'client' => $client->load('user'),
            'total_paid' => $totalPaid,
            'monthly_payment' => $monthlyPayment,
            'balance' => $balance,
            'payments' => $client->payments,
            'current_plan' => $client->plans->where('active', true)->first()?->plan
        ]);
    }
}
