<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateContactAPIRequest;
use App\Http\Requests\API\UpdateContactAPIRequest;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ContactController
 * @package App\Http\Controllers\API
 */
class ContactAPIController extends AppBaseController
{
    /** @var  ContactRepository */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        parent::__construct();

        $this->contactRepository = $contactRepo;
    }

    /**
     * @api {get} /contacts List
     * @apiGroup Contact
     * @apiName List
     * @apiDescription Display a listing of the Contact.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Request} request
     *
     * @apiSuccess {Number} id Id of Contact
     * @apiSuccess {String} created_at Created at of Contact
     * @apiSuccess {String} updated_at Updated at of Contact
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
            $this->contactRepository->pushCriteria(new RequestCriteria($request));
            $this->contactRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // $limit = limit or per_page
        $perPage = (int)$request->get('per_page');
        $limit = (int)$request->get('limit', $perPage);
        $contacts = $this->contactRepository->paginate($limit);

        return $this->sendResponse($contacts->toArray(), 'Contacts are retrieved successfully');
    }

    /**
      * @api {post} /contacts Create
      * @apiGroup Contact
      * @apiName StoreContact
      * @apiDescription Store a newly created Contact in storage.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {CreateContactAPIRequest} request
      *
      * @apiSuccess {Number} id Id of Contact
      * @apiSuccess {String} created_at Created at of Contact
      * @apiSuccess {String} updated_at Updated at of Contact
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
     * @param CreateContactAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateContactAPIRequest $request)
    {
        $input = $request->all();

        $contacts = $this->contactRepository->create($input);

        return $this->sendResponse($contacts->toArray(), 'Contact is saved successfully');
    }

    /**
      * @api {get} /contacts/:id Detail
      * @apiGroup Contact
      * @apiName ShowContact
      * @apiDescription Display the specified Contact.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {Number} id Id of Contact
      *
      * @apiSuccess {Number} id Id of Contact
      * @apiSuccess {String} created_at Created at of Contact
      * @apiSuccess {String} updated_at Updated at of Contact
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
        /** @var Contact $contact */
        $contact = $this->contactRepository->findWithoutFail($id);

        if (empty($contact)) {
            return $this->sendError('Contact not found');
        }

        return $this->sendResponse($contact->toArray(), 'Contact is retrieved successfully');
    }

    /**
     * @api {put} /contacts/:id Update
     * @apiGroup Contact
     * @apiName UpdateContact
     * @apiDescription Update the specified Contact in storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {UpdateContactAPIRequest} request
     *
     * @apiSuccess {Number} id Id of Contact
     * @apiSuccess {String} created_at Created at of Contact
     * @apiSuccess {String} updated_at Updated at of Contact
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
     * @param UpdateContactAPIRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateContactAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Contact $contact */
        $contact = $this->contactRepository->findWithoutFail($id);

        if (empty($contact)) {
            return $this->sendError('Contact not found');
        }

        $contact = $this->contactRepository->update($input, $id);

        return $this->sendResponse($contact->toArray(), 'Contact is updated successfully');
    }

    /**
     * @api {delete} /contacts/:id Destroy
     * @apiGroup Contact
     * @apiName DestroyContact
     * @apiDescription Remove the specified Contact from storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Number} id Id of Contact
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
        /** @var Contact $contact */
        $contact = $this->contactRepository->findWithoutFail($id);

        if (empty($contact)) {
            return $this->sendError('Contact not found');
        }

        $contact->delete();

        return $this->sendResponse($id, 'Contact is deleted successfully');
    }
}
