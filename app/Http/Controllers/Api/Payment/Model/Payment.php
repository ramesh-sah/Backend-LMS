<?php

namespace App\Http\Controllers\Api\Payment\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\Dues\Model\Dues;
use App\Http\Controllers\Api\Employee\Model\Employee;
use App\Http\Controllers\Api\Issue\Model\Issue;
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes, FilterQueryString;

    protected $table='payments';
    protected $primaryKey = 'payment_id';

    protected $filters = [
        'sort',
        'like',
        'in',
    ];

    protected $fillable = [
        'paid_amount',
        'member_id',
        'employee_id',
        'book_id',
        'issue_id',
        'due_id',
    ];

    public function memberForeign()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function employeeForeign()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function bookForeign()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function issueForeign()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }
    public function duesForeign()
    {
        return $this->belongsTo(Dues::class, 'due_id');
    }

    protected $dates = ['deleted_at'];

}

