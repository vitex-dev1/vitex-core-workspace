<?php

namespace App\Http\Controllers\Backend;

use Lang;
use App\Models\User;
use App\Models\WorkspaceObject;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Http\Request;
use App\Facades\Helper;
use Flash;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\Backend
 */
class UserController extends BaseController
{
    /** @var UserRepository $userRepository */
    private $userRepository;
    /** @var WorkspaceRepository $workspaceRepository */
    private $workspaceRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepo
     * @param WorkspaceRepository $workspaceRepo
     */
    public function __construct(UserRepository $userRepo, WorkspaceRepository $workspaceRepo)
    {
        parent::__construct();

        $this->userRepository = $userRepo;
        $this->workspaceRepository = $workspaceRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->getAllUsers($request, User::USER_TYPE_ALL, User::PLATFORM_BACKOFFICE, $this->perPage);
        $roles = $this->userRepository->getRoleList($request, User::USER_TYPE_ALL, User::PLATFORM_BACKOFFICE);

        // Status
        $active_statuses = User::active_statuses();

        return view($this->guard.'.users.index', ['model' => $users])
            ->with(compact('roles', 'active_statuses'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $roles = $this->userRepository->getRoleList($request, User::USER_TYPE_ALL, User::PLATFORM_BACKOFFICE);
        $genders = User::genders();
        $workspaces = Helper::getActiveWorkspaces();

        return view($this->guard.'.users.create', ['model' => ''])
            ->with(compact('roles', 'genders', 'workspaces'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success(Lang::get('messages.user.created_successfully'));

        return redirect(route($this->guard.'.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function show(Request $request, User $user)
    {
        $roles = $this->userRepository->getRoleList($request, User::USER_TYPE_ALL, User::PLATFORM_BACKOFFICE);
        $genders = User::genders();
        $workspaces = Helper::getActiveWorkspaces();
        $workspaceUsers = WorkspaceObject::active()
            ->where('model', User::class)
            ->where('foreign_key', $user->id)
            ->get();

        return view($this->guard.'.users.show', ['model' => $user])
            ->with(compact('roles', 'genders', 'workspaces', 'workspaceUsers'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $me = $this->currentUser;
        // Clear filter workspace
        if ($me->isSuperAdmin()) {
            $workspaceId = 0;
        }

        $roles = $this->userRepository->getRoleList($request, User::USER_TYPE_ALL, User::PLATFORM_BACKOFFICE);
        $genders = User::genders();
        $workspaces = Helper::getActiveWorkspaces();
        $workspaceUsers = WorkspaceObject::active()
            ->where('model', User::class)
            ->where('foreign_key', $user->id)
            ->get();

        return view($this->guard.'.users.edit', ['model' => $user])
            ->with(compact('roles', 'genders', 'workspaces', 'workspaceUsers'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $id = $user->id;
        $input = $request->all();
        $changeEmail = false;

        if ($request->has('email')) {
            // When change email
            if($input['email'] != $user->email) {
                // Store new mail in temporary
                $input['email_tmp'] = $input['email'];
                $changeEmail = true;
            }

            // Not allow change email now
            unset($input['email']);
        }

        // Save data
        $user = $this->userRepository->update($input, $id);

        Flash::success(Lang::get('messages.admin.updated_successfully'));

        if ($changeEmail) {
            // Send mail
            $this->dispatch(new \App\Jobs\SendChangeMailConfirmation($user));
        }

        return redirect(route($this->guard.'.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function destroy(Request $request, User $user)
    {
        $id = $user->id;
        $this->userRepository->delete($id);

        Flash::success(Lang::get('messages.admin.deleted_successfully'));

        return redirect(route($this->guard.'.users.index'));
    }

    /**
     * Ban or Un-ban user
     *
     * @param Request $request
     * @param integer $id
     * @return Response
     */
    public function status(Request $request, int $id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(Lang::get('user.not_found'));

            return redirect(route($this->guard.'.users.index'));
        }

        $user->active = !$user->active;
        $user->save();

        Flash::success(Lang::get('messages.admin.updated_successfully'));

        return redirect()->back();
    }

    /**
     * Update the specified User in storage.
     *
     * @param Request $request
     *
     * @param string $token
     * @return Response
     */
    public function confirmChangeEmail(Request $request, $token)
    {
        // Decode object
        $data = json_decode(base64_decode($token), true);

        if (empty($data['id'])) {
            Flash::error(Lang::get('messages.user.not_found'));
        }

        /** @var \App\Models\User $user */
        $user = $this->userRepository->findWithoutFail($data['id']);

        if (empty($user)) {
            Flash::error(Lang::get('messages.user.not_found'));
        }

        $user->email = $user->email_tmp;
        $user->email_tmp = null;
        $user->save();

        Flash::success(Lang::get('messages.user.changed_email_successfully'));

        return redirect(route($this->guard.'.user.changedEmailSuccess'));
    }

    /**
     * Display the specified User.
     *
     * @return Response
     */
    public function changedEmailSuccess()
    {
        return view($this->guard.'.auth.emails.changed_email_success');
    }

    /**
     * Display the specified User.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function profile(Request $request)
    {
        $user = $this->currentUser;

        return view($this->guard.'.users.profile', ['model' => $user]);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request)
    {
        $user = $this->currentUser;
        $roles = $this->userRepository->getRoleList($request, User::USER_TYPE_ALL);
        $genders = User::genders();
        $isProfile = true;

        return view($this->guard.'.users.edit_profile', ['model' => $user])
            ->with(compact('roles', 'genders', 'isProfile'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateProfile(UpdateUserRequest $request)
    {
        $user = $this->currentUser;
        $id = $user->id;
        $input = $request->all();
        $changeEmail = false;

        if ($request->has('email')) {
            // When change email
            if($input['email'] != $user->email) {
                // Store new mail in temporary
                $input['email_tmp'] = $input['email'];
                $changeEmail = true;
            }

            // Not allow change email now
            unset($input['email']);
        }

        // Save data
        $user = $this->userRepository->update($input, $id);

        Flash::success(Lang::get('messages.admin.profile_updated_successfully'));

        if ($changeEmail) {
            // Send mail
            $this->dispatch(new \App\Jobs\SendChangeMailConfirmation($user));
            Flash::success(Lang::get('user.email_changed_successfully'));
        }

        return redirect(route($this->guard.'.users.profile'));
    }
}
