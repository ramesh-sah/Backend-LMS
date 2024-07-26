<?php

namespace App\Http\Controllers\Helpers\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortHelper

{
    public static function applySorting($query, $sortBy, $sortOrder)
    {
        if ($sortBy && $sortOrder) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }
}
