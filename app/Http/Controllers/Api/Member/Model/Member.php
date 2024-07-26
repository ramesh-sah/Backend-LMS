<?php

namespace App\Http\Controllers\Api\Member\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes;

    protected $guard = 'member'; //set the guard variable

    protected $primaryKey = 'member_id'; // Set the primary key to 'user_id'
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'roll_number',
        'address',
        'gender',
        'email',
        'password',
        'contact_number',
        'enrollment_year',
        'role',
        'account_status',
        'image_link'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
