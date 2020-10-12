<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Helper;
use Auth;
use DB;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends AppBaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'photo',
        'description',
        'is_super_admin',
        'is_admin',
        'remember_token',
        'created_at',
        'updated_at',
        'role_id',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    /**
     * @param Request $request
     * @param int $userType User type. 0: User, 1: Admin.
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getAllUsers(Request $request, $userType = 0, $platform = 0, $perPage = 10)
    {
        /** @var \App\Models\User $me */
        $me = Auth::guard('admin')->user();

        /** @var \App\Models\User $userInstance */
        $userInstance = User::getInstance();

        $this->scopeQuery(function (\Illuminate\Database\Eloquent\Model $model) use ($request, $userInstance, $me, $userType, $platform) {
            // Relation
            $model = $model->with(['role']);
            // Order
            $model = $model->orderBy('users.' . $userInstance->getKeyName(), User::ORDER_DESC);
            $model = $model->where('users.platform', $platform);

            // Filter by type (is admin or not)
            if (in_array($userType, [User::USER_TYPE_ADMIN, User::USER_TYPE_NORMAL])) {
                $model = $model->where('users.is_admin', ($userType == User::USER_TYPE_ADMIN) ? User::IS_YES : User::IS_NO);
            }

            // Except me
            $model = $model->whereNotIn('users.' . $userInstance->getKeyName(), [$me->id, User::SUPER_ADMIN_ID])
                ->where('users.is_super_admin', false);

            // Search by keyword
            if ($request->has('keyword') && $request->get('keyword') != '') {
                $keyword = $request->get('keyword');

                $model = $model->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('users.email', 'LIKE', '%' . $keyword . '%');
                });
            }

            // Filter by role
            if ($request->has('role_id') && $request->get('role_id') != '') {
                $model = $model->where('users.role_id', (int)$request->get('role_id'));
            }

            // Filter by active status
            if ($request->has('active') && $request->get('active') != '') {
                $model = $model->where('users.active', (int)$request->get('active'));
            }

            // Filter by verify status
            if ($request->has('is_verified') && $request->get('is_verified') != '') {
                $model = $model->where('users.is_verified', (int)$request->get('is_verified'));
            }

            return $model;
        });

        $this->pushCriteria(new RequestCriteria($request));
        $users = $this->paginate($perPage);

        return $users;
    }

    /**
     * Get role list
     *
     * @param Request $request
     * @param int $userType User type. 0: User, 1: Admin.
     * @return \Illuminate\Support\Collection
     */
    public function getRoleList(Request $request, $userType = 0, $platform = 0)
    {
        /** @var \App\Models\Role $roleInstance */
        $roleInstance = Role::getInstance();
        $roles = Role::where('roles.active', Role::IS_YES);
        $roles->where('roles.platform', $platform);

        // With User List, Role = User
        if ($userType == User::USER_TYPE_ADMIN) {
            // Other admin roles
            $roles->whereNotIn('roles.' . $roleInstance->getKeyName(), $roleInstance->getNormalUserRoles());
        } else if ($userType == User::USER_TYPE_NORMAL) {
            // User
            $roles->whereIn('roles.' . $roleInstance->getKeyName(), $roleInstance->getNormalUserRoles());
        }

        // Get list
        /** @var \Illuminate\Support\Collection $roles */
        $roles = $roles->pluck('roles.name', 'roles.' . $roleInstance->getKeyName());
        // Translate
        $roles->transform(function ($item, $key) {
            return Helper::trans($item, [], 'role');
        });

        return $roles;
    }

    /**
     * Attach multi workspaces and roles
     *
     * @param int $userId
     * @param array $data
     * @return array|null
     */
    public function attachWorkspaces(int $userId, array $data)
    {
        $response = null;

        if (count($data) > 0) {
            /** @var \App\WorkspaceObject $workspaceObject */
            $workspaceObject = \App\Models\WorkspaceObject::getInstance();
            /** @var \App\Role $roleInstance */
            $roleInstance = Role::getInstance();
            $response = [];

            // Attach workspaces
            foreach ($data as $item) {
                $metaData = [
                    'role_id' => $item['role_id'],
                    'is_admin' => in_array($item['role_id'], $roleInstance->getAdminRoles()),
                ];
                $response[] = $workspaceObject->attachObject($this->model(), $userId, $item['workspace_id'], $metaData);
            }
        }

        return $response;
    }

    /**
     * Attach multi workspaces and roles
     *
     * @param int $userId
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function reloadWorkspaces(int $userId, array $data)
    {
        $response = null;

        if (count($data) > 0) {
            /** @var \App\WorkspaceObject $workspaceObject */
            $workspaceObject = \App\Models\WorkspaceObject::getInstance();

            // Detach exist workspaces
            $workspaceObject->detachObject($this->model(), $userId);

            // Attach workspaces
            $response = $this->attachWorkspaces($userId, $data);
        }

        return $response;
    }

    /**
     * Overwrite create function from base
     *
     * @param array $attributes
     * @return \App\User
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \Exception
     */
    public function create(array $attributes)
    {
        // Begin transaction
        DB::beginTransaction();

        // Random password
        $password = User::randomPassword();
        $attributes['default_password'] = $password;
        $attributes['password'] = bcrypt($password);

        // Create new user
        $user = parent::create($attributes);

        // Attach workspace if set
        if (isset($attributes['workspaces'])) {
            // Assign user to workspace
            $this->attachWorkspaces($user->id, $attributes['workspaces']);
        }

        // Commit transaction
        DB::commit();

        // Send mail
        dispatch(new \App\Jobs\SendAdminPasswordEmail($user));

        return $user;
    }

    /**
     * Overwrite update function from base
     *
     * @param array $attributes
     * @param int $id
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(array $attributes, $id)
    {
        // Update user data
        $user = parent::update($attributes, $id);

        // Attach workspace if set
        if (isset($attributes['workspaces'])) {
            // Reload workspace roles
            $this->reloadWorkspaces($user->id, $attributes['workspaces']);
        }

        return $user;
    }

    /**
     * @param Request $request
     * @param int $userType User type. 0: User, 1: Admin.
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function checkUserBan($email = '')
    {
        $user = $this->makeModel()->where('email', $email)->first();

        if(!empty($user) && empty($user->deleted_at) && empty($user->active)) {
            return false;
        }

        return true;
    }
}
