<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRoleAPIRequest;
use App\Http\Requests\API\UpdateRoleAPIRequest;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RoleController
 * @package App\Http\Controllers\API
 */
class RoleAPIController extends AppBaseController
{
    /** @var  RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        parent::__construct();

        $this->roleRepository = $roleRepo;
    }

    /**
     * @api {get} /roles List
     * @apiGroup Role
     * @apiName List
     * @apiDescription Display a listing of the Role.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Request} request
     *
     * @apiSuccess {Number} id Id of Role
     * @apiSuccess {String} created_at Created at of Role
     * @apiSuccess {String} updated_at Updated at of Role
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "meta": {
     *             "success": true,
     *             "message": "success",
     *             "code": 200
     *         },
     *         "response": {
     *             "total": 19,
     *             "per_page": 5,
     *             "current_page": 1,
     *             "last_page": 4,
     *             "next_page_url": null,
     *             "prev_page_url": null,
     *             "from": 1,
     *             "to": 5,
     *             "data": [
     *                 {
     *                     "id": 23,
     *                     "created_at": "2018-06-01 05:41:11",
     *                     "updated_at": "2018-06-01 05:41:11"
     *                 }
     *             ]
     *         }
     *     }
     *
     * @apiError Error Get list failure
     * @apiErrorExample {json} Error-Response 500:
     *     HTTP/1.1 500 GetErrorFailure
     *     {
     *        "meta": {
     *            "success": false,
     *            "message": "failure",
     *            "code": 500,
     *            "errors": null
     *        },
     *        "response": null
     *     }
     *
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->roleRepository->pushCriteria(new RequestCriteria($request));
            $this->roleRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // $limit = limit or per_page
        $perPage = (int)$request->get('per_page');
        $limit = (int)$request->get('limit', $perPage);
        $roles = $this->roleRepository->paginate($limit);

        return $this->sendResponse($roles->toArray(), 'Roles are retrieved successfully');
    }

    /**
      * @api {post} /roles Create
      * @apiGroup Role
      * @apiName StoreRole
      * @apiDescription Store a newly created Role in storage.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {CreateRoleAPIRequest} request
      *
      * @apiSuccess {Number} id Id of Role
      * @apiSuccess {String} created_at Created at of Role
      * @apiSuccess {String} updated_at Updated at of Role
      *
      * @apiSuccessExample {json} Success-Response:
      *     HTTP/1.1 200 OK
      *     {
      *          "meta": {
      *             "success": true,
      *             "message": "success",
      *             "code": 200
      *          },
      *          "response": {
      *              "id": 1,
      *              "created_at": "2018-05-16 14:32:15",
      *              "updated_at": "2018-05-16 14:32:15"
      *          }
      *     }
      *
      * @apiError Error Get list failure
      * @apiErrorExample {json} Error-Response 500:
      *     HTTP/1.1 500 GetErrorFailure
      *     {
      *        "meta": {
      *            "success": false,
      *            "message": "failure",
      *            "code": 500,
      *            "errors": null
      *        },
      *        "response": null
      *     }
      *
      */
    /**
     * @param CreateRoleAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRoleAPIRequest $request)
    {
        $input = $request->all();

        $roles = $this->roleRepository->create($input);

        return $this->sendResponse($roles->toArray(), 'Role is saved successfully');
    }

    /**
      * @api {get} /roles/:id Detail
      * @apiGroup Role
      * @apiName ShowRole
      * @apiDescription Display the specified Role.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {Number} id Id of Role
      *
      * @apiSuccess {Number} id Id of Role
      * @apiSuccess {String} created_at Created at of Role
      * @apiSuccess {String} updated_at Updated at of Role
      *
      * @apiSuccessExample {json} Success-Response:
      *     HTTP/1.1 200 OK
      *     {
      *          "meta": {
      *             "success": true,
      *             "message": "success",
      *             "code": 200
      *          },
      *          "response": {
      *              "id": 1,
      *              "created_at": "2018-05-16 14:32:15",
      *              "updated_at": "2018-05-16 14:32:15"
      *          }
      *     }
      *
      * @apiError Error Get list failure
      * @apiErrorExample {json} Error-Response 500:
      *     HTTP/1.1 500 GetErrorFailure
      *     {
      *        "meta": {
      *            "success": false,
      *            "message": "failure",
      *            "code": 500,
      *            "errors": null
      *        },
      *        "response": null
      *     }
      *
      */
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Role $role */
        $role = $this->roleRepository->findWithoutFail($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        return $this->sendResponse($role->toArray(), 'Role is retrieved successfully');
    }

    /**
     * @api {put} /roles/:id Update
     * @apiGroup Role
     * @apiName UpdateRole
     * @apiDescription Update the specified Role in storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {UpdateRoleAPIRequest} request
     *
     * @apiSuccess {Number} id Id of Role
     * @apiSuccess {String} created_at Created at of Role
     * @apiSuccess {String} updated_at Updated at of Role
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "meta": {
     *             "success": true,
     *             "message": "success",
     *             "code": 200
     *          },
     *          "response": {
     *              "id": 1,
     *              "created_at": "2018-05-16 14:32:15",
     *              "updated_at": "2018-05-16 14:32:15"
     *          }
     *     }
     *
     * @apiError Error Get list failure
     * @apiErrorExample {json} Error-Response 500:
     *     HTTP/1.1 500 GetErrorFailure
     *     {
     *        "meta": {
     *            "success": false,
     *            "message": "failure",
     *            "code": 500,
     *            "errors": null
     *        },
     *        "response": null
     *     }
     *
     */
    /**
     * @param UpdateRoleAPIRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Role $role */
        $role = $this->roleRepository->findWithoutFail($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        $role = $this->roleRepository->update($input, $id);

        return $this->sendResponse($role->toArray(), 'Role is updated successfully');
    }

    /**
     * @api {delete} /roles/:id Destroy
     * @apiGroup Role
     * @apiName DestroyRole
     * @apiDescription Remove the specified Role from storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Number} id Id of Role
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "meta": {
     *             "success": true,
     *             "message": "success",
     *             "code": 200
     *          },
     *          "response": null
     *     }
     *
     * @apiError Error Get list failure
     * @apiErrorExample {json} Error-Response 500:
     *     HTTP/1.1 500 GetErrorFailure
     *     {
     *        "meta": {
     *            "success": false,
     *            "message": "failure",
     *            "code": 500,
     *            "errors": null
     *        },
     *        "response": null
     *     }
     *
     */
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /** @var Role $role */
        $role = $this->roleRepository->findWithoutFail($id);

        if (empty($role)) {
            return $this->sendError('Role not found');
        }

        $role->delete();

        return $this->sendResponse($id, 'Role is deleted successfully');
    }
}
