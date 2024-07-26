<?php

namespace App\Http\Controllers\Api\Notification\Model;

use App\Traits\UpdateCreator;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Notification extends Model
{
    use HasFactory, FilterQueryString, HasUuids, SoftDeletes, FilterQueryString, UpdateCreator;

    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
        'title',
        'type',
        'message'
    ];
    protected $dates = ['deleted_at'];

    
}
