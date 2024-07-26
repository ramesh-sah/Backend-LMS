<?php

namespace App\Http\Controllers\Api\NewBookRequest\Model;

use App\Http\Controllers\Api\Employee\Model\Employee;
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class NewBookRequest extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'new_book_requests';
    protected $primaryKey = 'request_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
       'book_name',
       'author_name',
       'member_id',
       'employee_id',
    ];
    public function memberForeign()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function employeeForeign()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    protected $dates = ['deleted_at'];

    
}
