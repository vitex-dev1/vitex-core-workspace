<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Workspace
 * @package App
 * @version January 8, 2019, 7:37 am UTC
 *
 * @property integer user_id
 * @property string name
 * @property boolean active Active status
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property array providers JSON config for providers.<br>
 * Format example: {"bol":{"api_key":"apiiiii","secret_key":"secretttt","ftps_user":"user 2","ftps_password":"pass 2"}}
 *
 * @method int withUser(int $userId)
 */
class Workspace extends AppModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'workspaces';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'name',
        'active',
        'providers',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'name' => 'string',
        'active' => 'boolean',
        'providers' => 'array',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // Set created by user
            $model->user_id = (!empty(\Auth::guard('admin')) && !empty(\Auth::guard('admin')->user())) ? \Auth::guard('admin')->user()->id : 1;
        });
    }

    /**
     * Get default workspace
     *
     * @return \App\Workspace
     */
    public static function getDefault()
    {
        $workspace = static::where('active', static::IS_YES)->first();

        return $workspace;
    }

    /**
     * Scope a query to join with workspace_objects
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUser(\Illuminate\Database\Eloquent\Builder $model, int $userId)
    {
        $thisInstance = $this;
        $model->rightJoin('workspace_objects', function ($join) use ($thisInstance, $userId) {
            $join->on('workspace_objects.workspace_id', '=', $thisInstance->getTable() . '.' . $thisInstance->getKeyName());
            $join->where('workspace_objects.active', \App\Models\WorkspaceObject::IS_YES);
            $join->where('workspace_objects.model', \App\Models\User::class);
            $join->where('workspace_objects.foreign_key', $userId);
        });

        // Prevent duplicate
        $model->groupBy($thisInstance->getTable() . '.' . $thisInstance->getKeyName());

        return $model;
    }
}
