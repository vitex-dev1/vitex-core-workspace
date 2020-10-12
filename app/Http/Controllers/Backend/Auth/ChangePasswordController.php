<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Auth, Flash, Hash, Lang;

class ChangePasswordController extends BaseController
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    use ResetsPasswords;

    protected $linkRequestView;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware($this->guard);
        $this->linkRequestView = $this->guard.'.auth.passwords.change';
    }

    /**
     * Reset the given user's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePasswordForm()
    {
        return view($this->linkRequestView);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, $this->getResetValidationRules());
        /** @var \App\Models\User $user */
        $user = Auth::guard($this->guard)->user();
        $password = $request->input('new_password');
        $currentPassword = $request->input('current_password');

        // Validate current password
        if (!Hash::check($currentPassword, $user->password)) {
            Flash::error(Lang::get('messages.user.invalid_current_password'));
            return redirect()->back();
        }

        // Change password
        $this->resetPassword($user, $password);

        Flash::success(Lang::get('messages.user.changed_password_successfully'));
        return redirect()->back();
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules()
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6|same:new_password'
        ];
    }

}
