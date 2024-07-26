<?php

namespace App\Models\Permission;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;
use App\Traits\UpdateCreator;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Guard;

class Permission extends ModelsPermission
{
    use HasFactory, HasUuids, UpdateCreator;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'guard_name',
        'created_by',
        'updated_by'
    ];

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @return PermissionContract|Permission
     */
    public static function findOrCreate(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (!$permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName, 'created_by' => '', 'updated_by' => '']);
        }
        return $permission;
    }
}
