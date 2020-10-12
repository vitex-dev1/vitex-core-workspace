<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCountryAPIRequest;
use App\Http\Requests\API\UpdateCountryAPIRequest;
use App\Models\Country;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CountryController
 * @package App\Http\Controllers\API
 */
class CountryAPIController extends AppBaseController
{
    /** @var  CountryRepository */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepo)
    {
        parent::__construct();

        $this->countryRepository = $countryRepo;
    }

    /**
     * @api {get} /countries List
     * @apiGroup Country
     * @apiName List
     * @apiDescription Display a listing of the Country.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Request} request
     *
     * @apiSuccess {Number} id Id of Country
     * @apiSuccess {String} created_at Created at of Country
     * @apiSuccess {String} updated_at Updated at of Country
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
            $this->countryRepository->pushCriteria(new RequestCriteria($request));
            $this->countryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // $limit = limit or per_page
        $perPage = (int)$request->get('per_page');
        $limit = (int)$request->get('limit', $perPage);
        $countries = $this->countryRepository->paginate($limit);

        return $this->sendResponse($countries->toArray(), 'Countries are retrieved successfully');
    }

    /**
      * @api {post} /countries Create
      * @apiGroup Country
      * @apiName StoreCountry
      * @apiDescription Store a newly created Country in storage.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {CreateCountryAPIRequest} request
      *
      * @apiSuccess {Number} id Id of Country
      * @apiSuccess {String} created_at Created at of Country
      * @apiSuccess {String} updated_at Updated at of Country
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
     * @param CreateCountryAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCountryAPIRequest $request)
    {
        $input = $request->all();

        $countries = $this->countryRepository->create($input);

        return $this->sendResponse($countries->toArray(), 'Country is saved successfully');
    }

    /**
      * @api {get} /countries/:id Detail
      * @apiGroup Country
      * @apiName ShowCountry
      * @apiDescription Display the specified Country.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {Number} id Id of Country
      *
      * @apiSuccess {Number} id Id of Country
      * @apiSuccess {String} created_at Created at of Country
      * @apiSuccess {String} updated_at Updated at of Country
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
        /** @var Country $country */
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        return $this->sendResponse($country->toArray(), 'Country is retrieved successfully');
    }

    /**
     * @api {put} /countries/:id Update
     * @apiGroup Country
     * @apiName UpdateCountry
     * @apiDescription Update the specified Country in storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {UpdateCountryAPIRequest} request
     *
     * @apiSuccess {Number} id Id of Country
     * @apiSuccess {String} created_at Created at of Country
     * @apiSuccess {String} updated_at Updated at of Country
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
     * @param UpdateCountryAPIRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCountryAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Country $country */
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        $country = $this->countryRepository->update($input, $id);

        return $this->sendResponse($country->toArray(), 'Country is updated successfully');
    }

    /**
     * @api {delete} /countries/:id Destroy
     * @apiGroup Country
     * @apiName DestroyCountry
     * @apiDescription Remove the specified Country from storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Number} id Id of Country
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
        /** @var Country $country */
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        $country->delete();

        return $this->sendResponse($id, 'Country is deleted successfully');
    }
}
