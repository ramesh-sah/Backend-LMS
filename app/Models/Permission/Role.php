<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'guard_name',
        'created_by',
        'updated_by'
    ];

    public function setCreatedByAttribute($value)
    {
        // if value is blank set the value to the username of the logged in user
        // if no logged in user then set as "Unknown User"
        if ($value == "") {
            $user = Auth::user();
            if (isset($user->id)) {
                $this->attributes['created_by'] = $user->name;
            } else {
                $this->attributes['created_by'] = 'Unknown User';
            }
        } else {
            $this->attributes['created_by'] = $value;
        }
    }
    public function setUpdatedByAttribute($value)
    {
        if ($value == "") {
            $user = Auth::user();
            if (isset($user->id)) {
                $this->attributes['updated_by'] = $user->name;
            } else {
                $this->attributes['updated_by'] = 'Unknown User';
            }
        } else {
            $this->attributes['updated_by'] = $value;
        }
    }
}
