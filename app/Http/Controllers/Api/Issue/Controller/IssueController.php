<?php

namespace App\Http\Controllers\Api\Issue\Controller;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookReservation\Model\BookReservation;
use App\Http\Controllers\Api\Dues\Model\Dues;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Issue\Model\Issue;
use Illuminate\Http\Request;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;
use Carbon\Carbon;

class IssueController extends Controller
{
    public function getAllIssue(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = Issue::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('memberForeign', 'employeeForeign', 'membershipForeign', 'reservationForeign', 'bookForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign');



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
    public function postIssue(Request $request)
    {
        // Post request
        $request->validate([
            'due_date' => now()->addDays(14),
            'member_id' => 'required|exists:members,member_id',
            'book_id' => 'required|exists:books,book_id',
            'employee_id' => 'required|exists:employees,employee_id',
            'membership_id' => 'required|exists:memberships,membership_id',
            'reservation_id' => 'exists:book_reservations,reservation_id'

        ]);

        $book = Book::find($request->book_id);
        if ($book->book_status !== 'available') {
            return response()->json([
                'message' => 'Book is not available',
            ], 400);
        }

        // $book = Book::find($request->book_id);
        // if ($book->book_status !== 'available') {
        //     return response()->json([
        //         'message' => 'Book is not available',
        //     ], 400);
        // }

        // Check if a reservation exists and update its status
        if ($request->reservation_id) {
            $reservation = BookReservation::find($request->reservation_id);
            if ($reservation) {
                $reservation->reservation_status = 'approved';
                $reservation->save();
            }
        }

        // Update the book status to reserved
        $book->book_status = 'issued';
        $book->save();

        $issue = Issue::create($request->all()); // Create a new Issue instance

        // Create the Dues record associated with the Issue
        $due = new Dues([
            'description' => ' Fine Fees',
            'due_status' => 'pending',
            'member_id' => $issue->member_id,
            'employee_id' => $issue->employee_id,
            'book_id' => $issue->book_id,
            'issue_id' => $issue->issue_id,
            'due_date' => $issue->due_date
        ]);

        // Save the Dues record
        $issue->dues()->save($due);

        return response()->json([
            'message' => 'Successfully created',
            'issue' => $issue, // Return the created issue data
            'dues' => $due // Return the created dues data
        ]);
    }



    public function getIssue(string $issue_id)
    {
        // Find the specific resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        return response()->json([$issue]);
    }

    public function getSpecificUserAllIssue(Request $request, string $member_id)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params


        // Find the specific resource with eager loading of relationships
        $bookIssue = Issue::where('member_id', $member_id)
            ->with('memberForeign', 'employeeForeign', 'membershipForeign', 'reservationForeign', 'bookForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign')->get();

        if ($bookIssue->isEmpty()) {
            return response()->json(['message' => 'No issue found'], 404);
        }
        // Apply Sorting
        $bookIssue = SortHelper::applySorting($bookIssue, $sortBy, $sortOrder);

        // Apply Filtering
        $bookIssue = FilterHelper::applyFiltering($bookIssue, $filters);


        // Return the book along with its relationships
        return response()->json([$bookIssue]);
    }

    public function updateIssue(Request $request, string $issue_id)
    {
        // Update the resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        $issue->updateIssue($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'issue' => $issue // Return the updated issue data
        ]);
    }
    public function issueBookRenew(Request $request, string $issue_id)
    {
        $request->validate([
            'due_date' => now()->addDays(14),
            'renewal_request_date' => 'required|date',
            'renewal_count' => 'required',


        ]);

        // Find the issue
        $issue = Issue::find($issue_id);
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404);
        }

        // Check if renewal limit is reached
        if ($issue->renewal_count === 'third') {
            return response()->json(['message' => 'You cannot renew the book further.'], 403); // Forbidden
        }

        // Update renewal_count based on its current value
        $currentRenewalCount = $issue->renewal_count;
        $newRenewalCount = match ($currentRenewalCount) {
            'none' => 'first',
            'first' => 'second',
            'second' => 'third',
            default => $currentRenewalCount, // Keep the same if not in the enum
        };

        // Update the issue
        $issue->update([
            'due_date' => Carbon::now()->addDays(14), // Set due_date 14 days from now
            'renewal_request_date' => Carbon::now(), // Set renewal_request_date to current timestamp
            'renewal_count' => $newRenewalCount,
        ]);

        return response()->json([
            'message' => 'Successfully updated',
            'issue' => $issue,
        ]);
    }



    public function destroyIssue(string $issue_id)
    {
        // Delete the resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        $issue->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
