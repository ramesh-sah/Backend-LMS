<?php

namespace App\Http\Controllers\Api\Payment\Controller;

use App\Http\Controllers\Api\Dues\Model\Dues;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Payment\Model\Payment;
use Illuminate\Http\Request;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class PaymentController extends Controller
{
    public function getAllPayments(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = Payment::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('memberForeign', 'employeeForeign', 'bookForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign','issueForeign','duesForeign');



        // Get the paginated result
        $issue = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $issue,
            $perPage,
            $currentPage,
            $total
        );

        return response()->json([
            'data' => $paginatedResult->items(),
            'total' => $paginatedResult->total(),
            'per_page' => $paginatedResult->perPage(),
            'current_page' => $paginatedResult->currentPage(),
            'last_page' => $paginatedResult->lastPage(),
        ], 200);
    }

    public function postPayment(Request $request)
    {
        // Validate the request
        $request->validate([
            'due_id' => 'required|exists:dues,id',
        ]);

        // Find the dues record
        $dues = Dues::find($request->due_id);

        // Check if dues exists
        if (!$dues) {
            return response()->json(['message' => 'Dues not found'], 404);
        }

        // Create a new Payment instance with validated data
        $payment = Payment::create([
            'due_id' => $request->due_id,
            'paid_amount'=>$dues->amount,
            'member_id'=>$dues->member_id,
            'employee_id'=>$dues->employee_id,
            'book_id'=>$dues->book_id,
            'issue_id'=>$dues->issue_id
        ]);

        // Update the dues status to 'cleared'
        $dues->status = 'cleared';
        $dues->save();

        return response()->json([
            'message' => 'Successfully created',
            'payment' => $payment
        ], 201);
    }

    public function getPaymentsByMemberId(Request $request , string $member_id)
    {
        // Get all payments associated with the member's dues
        $payments = Payment::whereHas('due', function ($query) use ($member_id) {
            $query->where('member_id', $member_id); // Assuming dues has a user_id field
        })->get();

        return response()->json([
            'payments' => $payments
        ],200);
    }

   

    public function updatePayment(Request $request, string $payment_id)
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

    public function destroyPayment(string $payment_id)
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
