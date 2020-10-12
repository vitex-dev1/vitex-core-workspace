<?php

/*
  |--------------------------------------------------------------------------
  | Backend Routes
  |--------------------------------------------------------------------------
  |
  | Here is where all Backend routes are defined.
  |
 */

$this->get('logout', ['as' => $this->admin.'.logout', 'uses' => 'Auth\AuthController@logout']);
// Change password
$this->get('password/change-password', ['as' => $this->admin.'.password.changePasswordForm', 'uses' => 'Auth\ChangePasswordController@changePasswordForm']);
$this->post('password/change-password', ['as' => $this->admin.'.password.changePassword', 'uses' => 'Auth\ChangePasswordController@changePassword']);
// Users
$this->put('users/status/{id}', ['as' => $this->admin.'.users.status', 'uses' => 'UserController@status']);
$this->get('users/profile', ['as' => $this->admin.'.users.profile', 'uses' => 'UserController@profile']);
$this->get('users/change-profile', ['as' => $this->admin.'.users.editProfile', 'uses' => 'UserController@editProfile']);
$this->put('users/update-profile', ['as' => $this->admin.'.users.updateProfile', 'uses' => 'UserController@updateProfile']);
$this->patch('users/update-profile', ['as' => $this->admin.'.users.updateProfile', 'uses' => 'UserController@updateProfile']);
$this->resource('users', 'UserController', ['as' => $this->admin]);
// Countries
$this->resource('countries', 'CountryController', ['as' => $this->admin]);
// Contacts
$this->resource('contacts', 'ContactController', ['as' => $this->admin]);
// Roles
$this->resource('roles', 'RoleController', ['as' => $this->admin]);
// Banners
$this->put('banners/change-order', ['as' => $this->admin.'.banners.changeOrder', 'uses' => 'BannerController@changeOrder']);
$this->resource('banners', 'BannerController', ['as' => $this->admin]);
// Workspaces
$this->get('workspaces/get-roles', ['as' => $this->admin.'.workspaces.getRoles', 'uses' => 'WorkspaceController@ajaxGetRoles']);
$this->resource('workspaces', 'WorkspaceController', ['as' => $this->admin]);
// Langs
$this->resource('langs', 'LangController', ['as' => $this->admin]);

Route::group([
    'prefix' => 'workspace/{workspace}',
], function () {
    // Dashboard
    Route::resource('dashboard', 'DashboardController', ['as' => $this->admin]);
});