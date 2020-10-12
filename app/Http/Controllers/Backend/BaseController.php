<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Auth;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * Class BackendController
 * @package App\Http\Controllers\Frontend
 */
class BaseController extends Controller
{

    /**
     * @var int $perPage
     */
    protected $perPage;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable $currentUser
     */
    protected $currentUser;
    /**
     * @var string $guard
     */
    protected $guard = 'admin';

    /**
     * BackendController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->perPage = config('common.pagination');
        $this->currentUser = null;

        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::guard($this->guard)->check() ? Auth::guard($this->guard)->user() : null;

            return $next($request);
        });
    }

    /**
     * @param $result
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    /**
     * @param $error
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

}
