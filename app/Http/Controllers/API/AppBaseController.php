<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function getAuthUser(Request $request) {
        $token = str_replace('Bearer ', null, $request->header('Authorization'));
        return JWTAuth::toUser($token);
    }

    public function sendResponse($result, $message) {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404) {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

}
