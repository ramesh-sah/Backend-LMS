<?php

namespace App\Http\Controllers\Api\Due\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'due_id';
    protected $fillable = [
        'description',
        'amount',
        'due_status',
        'member_id',
        'employee_id',
        'book_id',
        'issue_id'
    ];
}
