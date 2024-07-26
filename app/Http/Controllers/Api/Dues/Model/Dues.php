<?php

namespace App\Http\Controllers\Api\Publisher\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Publishers extends Model
{
    use HasFactory;

    protected $table = 'publishers';
    protected $primaryKey = 'publisher_id';
    protected $fillable = [
        'publisher_name',
        'publication_place',
    ];

    // Static counter for custom IDs
    // private static $counter = 0;

    // public static function create(array $attributes = [])
    // {
    //     // Generate a custom ID
    //     self::$counter++;
    //     $customId = 'pub-' . str_pad(self::$counter, 4, '0', STR_PAD_LEFT);

    //     // Set the custom ID as the publisher_id
    //     $attributes['publisher_id'] = $customId;

    //     // Create the publisher
    //     return parent::create($attributes);
    // }
}
