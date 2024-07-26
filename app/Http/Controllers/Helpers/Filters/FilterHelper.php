<?php

namespace App\Http\Controllers\Helpers\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterHelper
{
    public static function applyFiltering($query, $filters)
    {
        if (is_array($filters)) {
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    $query->where($key, 'like', '%' . $value . '%');
                }
            }
        }

        return $query;
    }
}
