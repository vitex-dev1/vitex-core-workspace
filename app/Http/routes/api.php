<?php

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where all API routes are defined.
  |
 */


/*
|--------------------------------------------------------------------------
| API routes of Country
|--------------------------------------------------------------------------
*/
$this->group(['prefix' => 'api/v1', 'middleware' => ['api', 'jwt.auth']], function () {
    $this->get('countries', ['as' => 'api.countries.index', 'uses' => 'CountryAPIController@index']);
    $this->post('countries', ['as' => 'api.countries.index', 'uses' => 'CountryAPIController@store']);
    $this->get('countries/{countries}', ['as' => 'api.countries.show', 'uses' => 'CountryAPIController@show']);
    $this->put('countries/{countries}', ['as' => 'api.countries.update', 'uses' => 'CountryAPIController@update']);
    $this->patch('countries/{countries}', ['as' => 'api.countries.patch', 'uses' => 'CountryAPIController@update']);
    $this->delete('countries{countries}', ['as' => 'api.countries.destroy', 'uses' => 'CountryAPIController@destroy']);
});


/*
|--------------------------------------------------------------------------
| API routes of Contact
|--------------------------------------------------------------------------
*/
$this->group(['prefix' => 'api/v1', 'middleware' => ['api', 'jwt.auth']], function () {
    $this->get('contacts', ['as' => 'api.contacts.index', 'uses' => 'ContactAPIController@index']);
    $this->post('contacts', ['as' => 'api.contacts.index', 'uses' => 'ContactAPIController@store']);
    $this->get('contacts/{contacts}', ['as' => 'api.contacts.show', 'uses' => 'ContactAPIController@show']);
    $this->put('contacts/{contacts}', ['as' => 'api.contacts.update', 'uses' => 'ContactAPIController@update']);
    $this->patch('contacts/{contacts}', ['as' => 'api.contacts.patch', 'uses' => 'ContactAPIController@update']);
    $this->delete('contacts{contacts}', ['as' => 'api.contacts.destroy', 'uses' => 'ContactAPIController@destroy']);
});


/*
|--------------------------------------------------------------------------
| API routes of Role
|--------------------------------------------------------------------------
*/
$this->group(['prefix' => 'api/v1', 'middleware' => ['api', 'jwt.auth']], function () {
    $this->get('roles', ['as' => 'api.roles.index', 'uses' => 'RoleAPIController@index']);
    $this->post('roles', ['as' => 'api.roles.index', 'uses' => 'RoleAPIController@store']);
    $this->get('roles/{roles}', ['as' => 'api.roles.show', 'uses' => 'RoleAPIController@show']);
    $this->put('roles/{roles}', ['as' => 'api.roles.update', 'uses' => 'RoleAPIController@update']);
    $this->patch('roles/{roles}', ['as' => 'api.roles.patch', 'uses' => 'RoleAPIController@update']);
    $this->delete('roles{roles}', ['as' => 'api.roles.destroy', 'uses' => 'RoleAPIController@destroy']);
});


/*
|--------------------------------------------------------------------------
| API routes of Banner
|--------------------------------------------------------------------------
*/
$this->group(['prefix' => 'api/v1', 'middleware' => ['api', 'jwt.auth']], function () {
    $this->get('banners', ['as' => 'api.banners.index', 'uses' => 'BannerAPIController@index']);
    $this->post('banners', ['as' => 'api.banners.index', 'uses' => 'BannerAPIController@store']);
    $this->get('banners/{banners}', ['as' => 'api.banners.show', 'uses' => 'BannerAPIController@show']);
    $this->put('banners/{banners}', ['as' => 'api.banners.update', 'uses' => 'BannerAPIController@update']);
    $this->patch('banners/{banners}', ['as' => 'api.banners.patch', 'uses' => 'BannerAPIController@update']);
    $this->delete('banners{banners}', ['as' => 'api.banners.destroy', 'uses' => 'BannerAPIController@destroy']);
});
