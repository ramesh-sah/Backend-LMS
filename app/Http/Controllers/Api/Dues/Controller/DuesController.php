<?php

namespace App\Http\Controllers\Api\Dues\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Dues\Model\Dues;
use Illuminate\Http\Request;
use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class DuesController extends Controller
{
    public function getAllDues(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = Dues::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('memberForeign', 'employeeForeign', 'bookForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign','issueForeign');



        // Get the paginated result
        $dues = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $dues,
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
        ]);
    }

    

      public function getSpecificUserAllDues(Request $request ,string $member_id )
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        // Find the specific resource with eager loading of relationships
        $duesCheck = Dues::where('member_id', $member_id)
            ->with('memberForeign', 'employeeForeign', 'bookForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign', 'issueForeign')->get();

        if ($duesCheck->isEmpty()) {
            return response()->json(['message' => 'No dues found']);
        }
        // Apply Sorting
        $duesCheck= SortHelper::applySorting($duesCheck, $sortBy, $sortOrder);

        // Apply Filtering
        $duesCheck = FilterHelper::applyFiltering($duesCheck, $filters);

      
        // Return the book along with its relationships
        return response()->json([$duesCheck]);
    }
}