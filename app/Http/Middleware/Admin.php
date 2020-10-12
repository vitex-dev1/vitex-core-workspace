<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Facades\Helper;

class Admin
{
    private $except = [
        'App\Http\Controllers\Backend\Auth\AuthController@logout',
        'App\Http\Controllers\Backend\Auth\ChangePasswordController@changePasswordForm',
        'App\Http\Controllers\Backend\Auth\ChangePasswordController@changePassword',
        'App\Http\Controllers\Backend\UserController@profile',
        'App\Http\Controllers\Backend\UserController@editProfile',
        'App\Http\Controllers\Backend\UserController@updateProfile',
        'App\Http\Controllers\Backend\UserController@updateProfile',
        'App\Modules\ContentManager\Controllers\MediaController@index',
        'App\Modules\ContentManager\Controllers\MediaController@store',
        'App\Modules\ContentManager\Controllers\MediaController@images',
        'App\Modules\ContentManager\Controllers\MediaController@destroy',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        // User is guest
        if (\Auth::guard($guard)->guest()) {
            return redirect('/admin');
        }

        $user = \Auth::guard($guard)->user();

        // When is not admin user
        if (!$user->isAdmin() || $user->platform != User::PLATFORM_BACKOFFICE) {
            return redirect('/admin');
        }

        if(empty($user->active)) {
            return abort(403, json_encode([
                'link' => route($guard.'.login'),
                'user' => $user
            ]));
        }

        // User is super admin
        if ($user->isSuperAdmin()) {
            return $next($request);
        } else {
            // Get workspace from request
            $workspaceId = $request->workspace;

            if (empty($workspaceId)) {
                // Get default workspace
                $workspace = Helper::getDefaultWorkspace();

                if (!empty($workspace)) {
                    $workspaceId = $workspace->id;
                }
            }

            // Get role from workspace
            $workspaceObject = new \App\Models\WorkspaceObject();
            $role = $workspaceObject->getRole($workspaceId, $user->id);
            $roleId = $role->id;
        }

        // Get role from cache if exist
        $roleCacheName = config('cache.key').$roleId;

        if (cache()->has($roleCacheName)) {
            $rolePermission = cache($roleCacheName);
        } else {
            $rolePermission = !empty($role) ? $role->toArray() : [];
            cache()->forever($roleCacheName, $rolePermission);
        }

        if (!empty($rolePermission)) {
            $user->setRelation('roles', new \App\Models\Role($rolePermission));
        }

        // Check permission by user role (permission)
        $role = $user->roles;

        if (!empty($role) && !$role->active) {
            return route($guard.'.login');
        }

        // Check user has access
        $routeName = \Route::currentRouteName();
        $routeAction = \Route::currentRouteAction();

        if ($this->userHasAccessTo($request) || in_array($routeAction, $this->except)) {
            return $next($request);
        }

        if(!empty($this->accessByScope($routeName, $role->permission))) {
            return $next($request);
        }

        // Request ajax
        if ($request->ajax()) {
            return response()->json(['status' => 403, 'success' => false, 'message' => 'Unauthorised.'], 403);
        }

        return abort(403, json_encode([
            'link' => route($guard.'.login'),
            'user' => $user
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Additional helper methods for the handle method
    |--------------------------------------------------------------------------
    */
    /**
     * Check permission scope
     *
     * @param string $routeName
     * @param array $permissions
     * @return Boolean true if has permission otherwise false
     */
    protected function accessByScope($routeName, $permissions)
    {
        $permissionScope = config('permission_scope.depend');
        $representPermissions = [];

        if(!empty($permissionScope)) {
            foreach ($permissionScope as $key => $value) {
                if(in_array($routeName, $value)) {
                    $representPermissions[] = $key;
                }
            }
        }

        if(!empty($representPermissions)) {
            $allPermissionDb = [];

            foreach ($permissions as $permission) {
                $allPermissionDb = array_merge($allPermissionDb, $permission);
            }

            if(!empty(array_intersect($allPermissionDb,$representPermissions))) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Additional helper methods for the handle method
    |--------------------------------------------------------------------------
    */
    /**
     * Checks if user has access to this requested route
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $guard
     * @return Boolean true if has permission otherwise false
     */
    protected function userHasAccessTo($request, $guard = 'admin')
    {
        return $this->hasPermission($request, $guard);
    }

    /**
     * hasPermission Check if user has requested route permission
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $guard
     * @return Boolean true if has permission otherwise false
     */
    protected function hasPermission($request, $guard = 'admin')
    {
        $required = $this->requiredPermission($request, $guard);

        return !$this->forbiddenRoute($request) && \Auth::guard($guard)->user()->hasPermission($required);
    }

    /**
     * Extract required permission from requested route
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $guard
     * @return array permission_slug connected to the Route
     */
    protected function requiredPermission($request, $guard = 'admin')
    {
        $action = $request->route()->getAction();
        $required = [];

        if (isset($action['controller'])) {
            $controller = isset($action['namespace']) ? explode("{$action['namespace']}\\", $action['controller']) : [];

            $required = !empty($controller) ? (array)$controller[1] : (array)$action['controller'];
        }

        return $required;
    }

    /**
     * Check if current route is hidden to current user role
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $guard
     * @return Boolean true/false
     */
    protected function forbiddenRoute($request, $guard = 'admin')
    {
        $action = $request->route()->getAction();

        if (isset($action['except'])) {
            return $action['except'] == \Auth::guard($guard)->user()->role->id;
        }

        return false;
    }
}
