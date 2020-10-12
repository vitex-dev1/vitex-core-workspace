<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBannerAPIRequest;
use App\Http\Requests\API\UpdateBannerAPIRequest;
use App\Models\Banner;
use App\Repositories\BannerRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class BannerController
 * @package App\Http\Controllers\API
 */
class BannerAPIController extends AppBaseController
{
    /** @var  BannerRepository */
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepo)
    {
        parent::__construct();

        $this->bannerRepository = $bannerRepo;
    }

    /**
     * @api {get} /banners List
     * @apiGroup Banner
     * @apiName List
     * @apiDescription Display a listing of the Banner.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Request} request
     *
     * @apiSuccess {Number} id Id of Banner
     * @apiSuccess {String} created_at Created at of Banner
     * @apiSuccess {String} updated_at Updated at of Banner
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
            $this->bannerRepository->pushCriteria(new RequestCriteria($request));
            $this->bannerRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // $limit = limit or per_page
        $perPage = (int)$request->get('per_page');
        $limit = (int)$request->get('limit', $perPage);
        $banners = $this->bannerRepository->paginate($limit);

        return $this->sendResponse($banners->toArray(), 'Banners are retrieved successfully');
    }

    /**
      * @api {post} /banners Create
      * @apiGroup Banner
      * @apiName StoreBanner
      * @apiDescription Store a newly created Banner in storage.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {CreateBannerAPIRequest} request
      *
      * @apiSuccess {Number} id Id of Banner
      * @apiSuccess {String} created_at Created at of Banner
      * @apiSuccess {String} updated_at Updated at of Banner
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
     * @param CreateBannerAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBannerAPIRequest $request)
    {
        $input = $request->all();

        $banners = $this->bannerRepository->create($input);

        return $this->sendResponse($banners->toArray(), 'Banner is saved successfully');
    }

    /**
      * @api {get} /banners/:id Detail
      * @apiGroup Banner
      * @apiName ShowBanner
      * @apiDescription Display the specified Banner.
      *
      * @apiPermission api
      * @apiPermission jwt.auth
      *
      * @apiHeader {String} Authorization Authorization Header
      *
      * @apiParam {Number} id Id of Banner
      *
      * @apiSuccess {Number} id Id of Banner
      * @apiSuccess {String} created_at Created at of Banner
      * @apiSuccess {String} updated_at Updated at of Banner
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
        /** @var Banner $banner */
        $banner = $this->bannerRepository->findWithoutFail($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        return $this->sendResponse($banner->toArray(), 'Banner is retrieved successfully');
    }

    /**
     * @api {put} /banners/:id Update
     * @apiGroup Banner
     * @apiName UpdateBanner
     * @apiDescription Update the specified Banner in storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {UpdateBannerAPIRequest} request
     *
     * @apiSuccess {Number} id Id of Banner
     * @apiSuccess {String} created_at Created at of Banner
     * @apiSuccess {String} updated_at Updated at of Banner
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
     * @param UpdateBannerAPIRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBannerAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Banner $banner */
        $banner = $this->bannerRepository->findWithoutFail($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        $banner = $this->bannerRepository->update($input, $id);

        return $this->sendResponse($banner->toArray(), 'Banner is updated successfully');
    }

    /**
     * @api {delete} /banners/:id Destroy
     * @apiGroup Banner
     * @apiName DestroyBanner
     * @apiDescription Remove the specified Banner from storage.
     *
     * @apiPermission api
     * @apiPermission jwt.auth
     *
     * @apiHeader {String} Authorization Authorization Header
     *
     * @apiParam {Number} id Id of Banner
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
        /** @var Banner $banner */
        $banner = $this->bannerRepository->findWithoutFail($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        $banner->delete();

        return $this->sendResponse($id, 'Banner is deleted successfully');
    }
}
