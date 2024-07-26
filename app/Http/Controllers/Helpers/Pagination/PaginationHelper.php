<?php

namespace App\Http\Controllers\Helpers\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginationHelper
{
    /**
     * Applies pagination to a collection of items.
     *
     * @param Collection|array $items The items to paginate (can be a Collection or an array).
     * @param int $perPage The number of items per page (limited to 20).
     * @param int $currentPage The current page number.
     * @param int $total The total number of items.
     * @return LengthAwarePaginator The paginated collection.
     */
    public static function applyPagination($items, int $perPage, int $currentPage, int $total): LengthAwarePaginator
    {
        // Convert array to Collection if needed
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        $perPage = min($perPage, 1); // Limit perPage to 5 

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
