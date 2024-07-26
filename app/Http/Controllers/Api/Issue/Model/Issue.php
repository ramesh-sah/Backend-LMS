<?php

namespace App\Http\Controllers\Api\Issue\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'issue_id';
    protected $fillable = [
        'due_date',
        'check_in_date',
        'renewal_request_date',
        'renewal_count',
        'member_id',
        'employee_id',
        'book_id'
    ];
}
