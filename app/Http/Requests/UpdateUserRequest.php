<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;

class UpdateUserRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Get data from route request
        $auth = null;
        $routeName = $this->route()->getName();

        if($routeName == 'admin.users.updateProfile') {
            $auth = auth(config('module.backend'))->user();
        } elseif ($routeName == 'client.users.updateProfile') {
            $auth = auth(config('module.client'))->user();
        }

        $user = $this->route('user');

        if(!empty($auth)) {
            $user = $auth;
        }

        $rules = array_merge(User::$rules, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id. ',id,deleted_at,NULL',
        ]);

        return $rules;
    }
}
