<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PermissionRole extends Model
{
    use HasFactory, HasRoles, HasUuids;

    protected $fillable = ['permission_id', 'role_id'];
}
