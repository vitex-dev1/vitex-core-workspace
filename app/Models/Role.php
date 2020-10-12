<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 * @package App
 * @version October 22, 2018, 4:00 am UTC
 *
 * @property int id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property bool platform Back-office, Client, Patient
 * @property bool active Active status
 * @property string name
 * @property string description
 * @property string permission
 */
class Role extends AppModel
{
    use SoftDeletes;

    const ROLE_ADMIN = 1;
    const ROLE_CLIENT = 2;
    const ROLE_PATIENT = 3;

    const PLATFORM_BACKOFFICE = 0;
    const PLATFORM_CLIENT = 1;
    const PLATFORM_PATIENT = 2;

    protected $dates = ['deleted_at'];

    public $table = 'roles';

    public $fillable = [
        'platform',
        'active',
        'name',
        'description',
        'permission',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'platform' => 'boolean',
        'active' => 'boolean',
        'name' => 'string',
        'description' => 'string',
        'permission' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    /**
     * @param bool $overwrite
     * @return bool
     */
    public function cachePermission($overwrite = true)
    {
        $key = config('cache.key').$this->id;

        // Ignore if not allow overwrite with exist file
        if (cache()->has($key) && !$overwrite) {
            return true;
        }

        $varContent = $this->toArray();

        return cache()->forever($key, $varContent);
    }

    /**
     * @return bool
     */
    public function cleanCachePermission()
    {
        $key = config('cache.key').$this->id;

        if (!cache()->has($key)) {
            return true;
        }

        return cache()->forget($key);
    }

    /**
     * Fire events when create, update roles
     * The "booting" method of the model.
     * @link https://stackoverflow.com/a/38685534
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // When saved
        static::saved(function ($model) {
            $model->cachePermission();
        });

        static::deleted(function ($model) {
            $model->cleanCachePermission();
        });
    }

    /**
     * Get init user role:
     * 1: Administrator
     * 2: User
     *
     * @return array
     */
    public function getHiddenRoles()
    {
        return [static::ROLE_PATIENT];
    }

    /**
     * Get normal user roles:
     * 2: User
     *
     * @return array
     */
    public function getNormalUserRoles()
    {
        return [static::ROLE_PATIENT];
    }

    /**
     * Get Admin user role
     *
     * @return array
     */
    public function getAdminRoles()
    {
        return [static::ROLE_ADMIN];
    }

    /**
     * Scope a query to only include platform roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope a query to join with workspace_objects
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param int $workspaceId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithWorkspace(\Illuminate\Database\Eloquent\Builder $model, int $workspaceId)
    {
        $thisInstance = $this;
        $model->select($thisInstance->getTable() . '.*')->rightJoin('workspace_objects', function ($join) use ($thisInstance, $workspaceId) {
            // Right join with workspace_objects table
            $join->where(function ($query) use ($thisInstance, $workspaceId) {
                $query->where('workspace_objects.foreign_key', \DB::raw($thisInstance->getTable() . '.' . $thisInstance->getKeyName()));
                $query->where('workspace_objects.workspace_id', $workspaceId);
                $query->where('workspace_objects.active', \App\Models\WorkspaceObject::IS_YES);
                $query->where('workspace_objects.model', static::class);
            });
            // Default include Administrator role
            $join->orWhere(function ($query) use ($thisInstance) {
                $query->orWhereIn($thisInstance->getTable() . '.' . $thisInstance->getKeyName(), $thisInstance->getAdminRoles());
            });
        });

        // Prevent duplicate
        $model->where('platform', self::PLATFORM_BACKOFFICE)->groupBy($thisInstance->getTable() . '.' . $thisInstance->getKeyName());

        return $model;
    }
}
