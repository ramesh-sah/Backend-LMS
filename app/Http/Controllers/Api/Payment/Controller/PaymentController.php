<?php

namespace App\Http\Controllers\Api\Payment\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Payment\Model\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Fetch all the Payment objects
        return Payment::all();
        // $payments = $query->simplePaginate(10);// Use the correct model name
    }

    public function store(Request $request)
    {
        // Post request
        $request->validate([
            'paid_amount' => 'required|numeric'
        ]);

        $payment = Payment::create($request->all()); // Create a new Payment instance
        return response()->json([
            'message' => 'Successfully created',
            'payment' => $payment // Return the created payment data
        ], 201);
    }

    public function show(string $payment_id)
    {
        // Find the specific resource
        $payment = Payment::find($payment_id); // Use the correct model name
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404); // Handle not found cases
        }
        return $payment;
    }

    public function update(Request $request, string $payment_id)
    {
        // Update the resource
        $payment = Payment::find($payment_id); // Use the correct model name
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404); // Handle not found cases
        }
        $payment->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'payment' => $payment // Return the updated payment data
        ], 200);
    }

    public function destroy(string $payment_id)
    {
        // Delete the resource
        $payment = Payment::find($payment_id); // Use the correct model name
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404); // Handle not found cases
        }
        $payment->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
