<?php

namespace App\Http\Controllers\Api\Issue\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookReservation\Model\BookReservation;
use App\Http\Controllers\Api\Employee\Model\Employee;
use App\Http\Controllers\Api\Member\Model\Member;
use App\Http\Controllers\Api\Membership\Model\Membership;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Issue extends Model
{
    use HasFactory, HasUuids, SoftDeletes, FilterQueryString;

    protected $table = 'issues';
    protected $primaryKey = 'issue_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];

    protected $fillable = [
        'due_date',
        'check_in_date',
        'renewal_request_date',
        'renewal_count',
        'member_id',
        'employee_id',
        'book_id',
        'membership_id',
        'reservation_id'
    ];
    protected $dates = ['deleted_at'];
    public function memberForeign()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function employeeForeign()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function membershipForeign()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }
    public function reservationForeign()
    {
        return $this->belongsTo(BookReservation::class, 'reservation_id');
    }
    public function bookForeign()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
