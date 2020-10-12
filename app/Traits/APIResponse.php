<?php

namespace App\Traits;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

trait APIResponse {
    public function sendResponse($result, $message) {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404) {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
}
