<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Blade;
use URL;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('pushonce', function ($expression) {
            $isDisplayed = '__pushonce_' . trim(substr($expression, 1, -1));
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush({$expression}); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        View::share('appTitle', config('app.name'));

        /**
         * @link https://stackoverflow.com/questions/29549660/get-laravel-5-controller-name-in-view#answer-29549985
         */
        app('view')->composer('*', function ($view) {
            $guard = 'web';
            $auth = null;
            $activeWorkspace = config('workspace.active');
            $middlewares = app('request')->route()->computedMiddleware;

            if(!empty($middlewares)) {
                if(in_array(config("module.backend"), $middlewares)) {
                    $guard = config("module.backend");
                } else {
                    $guard = 'web';
                }
            }

            $auth = auth($guard)->user();
            $view->with(compact('auth', 'guard', 'activeWorkspace'));
        });

        app('view')->composer('layouts.admin', function ($view) {
            $this->bootBaseParams($view);
        });
    }

    public function bootBaseParams($view) {
        $request = app('request');
        $route = $request->route();
        $routeName = $route->getName();
        $prefix = $route->getPrefix();
        $action = $route->getAction();
        $params = $route->parameters();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);
        $baseUrl = URL::to('/') . '/';
        $view->with(compact('prefix', 'controller', 'action', 'params', 'baseUrl', 'routeName', 'request'));
    }
}
