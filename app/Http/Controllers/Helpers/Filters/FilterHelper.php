<?php

namespace App\Http\Controllers\Helpers\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterHelper
{
    public static function applyFiltering($query, $filters)
    {
        // Ensure $filters is an array, default to an empty array if not
        if (!is_array($filters)) {
            $filters = [];
        }

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                // Skip or handle cases where $value is an array
                continue;
            }

            if (self::isNullCheck($key)) {
                $column = self::extractColumnName($key, '_is_null');
                if ($value) {
                    // Apply the condition to check for NULL
                    $query->whereNull($column);
                }
            } elseif (self::isNotNullCheck($key)) {
                $column = self::extractColumnName($key, '_is_not_null');
                if ($value) {
                    // Apply the condition to check for NOT NULL
                    $query->whereNotNull($column);
                }
            } elseif (!empty($value)) {
                // Apply the LIKE filter for other fields
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }

    private static function isNullCheck($key)
    {
        // Define a suffix to identify null checks
        return strpos($key, '_is_null') !== false;
    }

    private static function isNotNullCheck($key)
    {
        // Define a suffix to identify not null checks
        return strpos($key, '_is_not_null') !== false;
    }

    private static function extractColumnName($key, $suffix)
    {
        // Remove the suffix to get the column name
        return str_replace($suffix, '', $key);
    }
}
