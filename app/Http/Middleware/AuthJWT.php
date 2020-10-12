<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use App\Traits\APIResponse;

class AuthJWT {
    use APIResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = str_replace('Bearer ', null, $request->header('Authorization'));
        $this->setLocale($request);

        try {
            $user = JWTAuth::toUser($token);

            if(!empty($user)) {
                $request->request->add(['user' => $user]);
                // read the language from the request header
                $locale = $request->header('Content-Language');
                // if the header is missed
                if(!$locale){
                    // Push locale setting of user to session
                    if (!empty($user->locale)) {
                        $locale = $user->locale;
                    } else {
                        // take the default local language
                        $locale = config('app.locale');
                    }
                }

                // check the languages defined is supported
                if (array_key_exists($locale, config('languages'))) {
                    // set the local language
                    app()->setLocale($locale);
                }

                if(empty($user->company_id) || empty($user->company->active)) {
                    return $this->sendError(trans('api_auth.invalid_permission'), 403);
                }

                // get the response after the request is done
                $response = $next($request);
                // set Content Languages header in the response
                $response->headers->set('Content-Language', $locale);
                // return the response
                return $response;
            }

            return $this->sendError(trans('api_auth.token_is_invalid'), 401);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendError(trans('api_auth.token_is_invalid'), 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->sendError(trans('api_auth.token_is_expired'), 401);
            } else {
                return $this->sendError(trans('api_auth.something_is_wrong'), 401);
            }
        }

        return $next($request);
    }

    public function setLocale($request) {
        // read the language from the request header
        $locale = $request->header('Content-Language');
        // if the header is missed
        if(!$locale){
            // take the default local language
            $locale = config('app.locale');
        }

        // check the languages defined is supported
        if (array_key_exists($locale, config('languages'))) {
            // set the local language
            app()->setLocale($locale);
        }

        return $locale;
    }
}
