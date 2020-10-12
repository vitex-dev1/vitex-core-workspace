<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Provider;
use App\Http\Requests\CreateWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Repositories\WorkspaceRepository;
use App\Models\Role;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class WorkspaceController extends BaseController
{
    /** @var WorkspaceRepository $workspaceRepository */
    private $workspaceRepository;

    public function __construct(WorkspaceRepository $workspaceRepo)
    {
        parent::__construct();

        $this->workspaceRepository = $workspaceRepo;
    }

    /**
     * Display a listing of the Workspace.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->workspaceRepository->pushCriteria(new RequestCriteria($request));
        $workspaces = $this->workspaceRepository->paginate();

        return view('admin.workspaces.index')
            ->with('workspaces', $workspaces);
    }

    /**
     * Show the form for creating a new Workspace.
     *
     * @return Response
     */
    public function create()
    {
        // Shipment fulfillment options
        $fulfillmentOptions = $this->workspaceRepository->getFulfillmentOptions();

        return view('admin.workspaces.create')
            ->with(compact('fulfillmentOptions'));
    }

    /**
     * Store a newly created Workspace in storage.
     *
     * @param CreateWorkspaceRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkspaceRequest $request)
    {
        $input = $request->all();
        $this->workspaceRepository->create($input);

        Flash::success(Lang::get('workspace.created_successfully'));

        if (!$request->has('save_and_close')) {
            return redirect()->back();
        }

        return redirect(route('admin.workspaces.index'));
    }

    /**
     * Display the specified Workspace.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workspace = $this->workspaceRepository->findWithoutFail($id);

        if (empty($workspace)) {
            Flash::error(Lang::get('workspace.not_found'));

            return redirect(route('admin.workspaces.index'));
        }

        return view('admin.workspaces.show')->with('workspace', $workspace);
    }

    /**
     * Show the form for editing the specified Workspace.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workspace = $this->workspaceRepository->findWithoutFail($id);

        if (empty($workspace)) {
            Flash::error(Lang::get('workspace.not_found'));

            return redirect(route('admin.workspaces.index'));
        }

        return view('admin.workspaces.edit')
            ->with('workspace', $workspace);
    }

    /**
     * Update the specified Workspace in storage.
     *
     * @param  int              $id
     * @param UpdateWorkspaceRequest $request
     *
     * @return Response
     */
    public function update(UpdateWorkspaceRequest $request, $id)
    {
        $workspace = $this->workspaceRepository->findWithoutFail($id);

        if (empty($workspace)) {
            Flash::error(Lang::get('workspace.not_found'));

            return redirect(route('admin.workspaces.index'));
        }

        $input = $request->all();
        $this->workspaceRepository->update($input, $id);

        Flash::success(Lang::get('workspace.updated_successfully'));

        if (!$request->has('save_and_close')) {
            return redirect()->back();
        }

        return redirect(route('admin.workspaces.index'));
    }

    /**
     * Remove the specified Workspace from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workspace = $this->workspaceRepository->findWithoutFail($id);

        if (empty($workspace)) {
            Flash::error(Lang::get('workspace.not_found'));

            return redirect(route('admin.workspaces.index'));
        }

        $this->workspaceRepository->delete($id);

        Flash::success(Lang::get('workspace.deleted_successfully'));

        return redirect(route('admin.workspaces.index'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxGetRoles(Request $request)
    {
        /** @var \App\Role $roleInstance */
        $workspaceId = (int)$request->get('id');
        $roleInstance = Role::getInstance();
        $roles = $roleInstance->withWorkspace($workspaceId)
            ->select('roles.' . $roleInstance->getKeyName(), 'roles.name')
            ->get();

        return $this->sendResponse($roles, Lang::get('strings.success'));
    }

}
