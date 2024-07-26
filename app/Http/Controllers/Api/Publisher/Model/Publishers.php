<?php

namespace App\Http\Controllers\Api\Publisher\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Traits\UpdateCreator;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Mehradsadeghi\FilterQueryString\FilterQueryString; // Add this import

class Publishers extends Model
{
    use HasFactory, FilterQueryString, HasUuids, SoftDeletes, FilterQueryString, UpdateCreator; // Include the trait

    protected $table = 'publishers';
    protected $primaryKey = 'publisher_id';

    protected $filters = [
        'sort',
        'like',
        'in',
    ];

    protected $fillable = [
        'publisher_name',
        'publication_place',
    ];
    protected $dates = ['deleted_at'];
}
