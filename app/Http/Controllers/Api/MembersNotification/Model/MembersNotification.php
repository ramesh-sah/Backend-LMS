<?php

namespace App\Http\Controllers\Api\MembersNotification\Model;

use App\Http\Controllers\Api\Member\Model\Member;
use App\Http\Controllers\Api\Notification\Model\Notification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class MembersNotification extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'members_notifications';
    protected $primaryKey = 'member_notification_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
        'member_id',
        'notification_id',
        'isRead',
    ];
    public function memberForeign()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function notificationForeign()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }



    protected $dates = ['deleted_at'];

    
}
