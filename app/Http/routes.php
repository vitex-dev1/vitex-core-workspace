<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$this->get('/', function () {
    return redirect($this->admin. '/login');
});

$this->get('/'. $this->admin, function () {
    return redirect($this->admin. '/login');
});

$this->post($this->admin . '/login', ['as' => $this->admin.'.login', 'uses' => 'Backend\Auth\AuthController@login']);
$this->get($this->admin . '/login', ['as' => $this->admin.'.showlogin', 'uses' => 'Backend\Auth\AuthController@showLoginForm']);

// Password Reset Routes...
$this->get($this->admin. '/password/reset', ['as' => $this->admin.'.password.request', 'uses' => 'Backend\Auth\ForgotPasswordController@showLinkRequestForm']);
$this->post($this->admin. '/password/email', ['as' => $this->admin.'.password.email', 'uses' => 'Backend\Auth\ForgotPasswordController@sendResetLinkEmail']);
$this->get($this->admin. '/password/reset/{token}', ['as' => $this->admin.'.password.reset', 'uses' => 'Backend\Auth\ResetPasswordController@showResetForm']);
$this->post($this->admin. '/password/reset', ['as' => $this->admin.'.password.resetPost', 'uses' => 'Backend\Auth\ResetPasswordController@reset']);

// Change language
$this->get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

// Change mail
$this->get($this->admin. '/confirm-change-email/{token}', ['as' => $this->admin.'.user.confirmChangeEmail', 'uses' => 'Backend\UserController@confirmChangeEmail']);
$this->get($this->admin. '/changed-email-success', ['as' => $this->admin.'.user.changedEmailSuccess', 'uses' => 'Backend\UserController@changedEmailSuccess']);