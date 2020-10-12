<?php

$orginalTranslations = [
    'invalid_email_or_password'         => 'The email or password you have entered is not correct. Please try again!',
    'failed_to_create_token'            => 'Creating token is failed',
    'login_success'                     => 'You have been logged in successfully',
    'get_profile_success'               => 'You have got profile successfully',
    'token_is_invalid'                  => 'Token is invalid',
    'token_is_expired'                  => 'Token is expired',
    'something_is_wrong'                => 'Something is wrong',
    'language_not_supported'            => 'Language not supported',
    'invalid_current_password'          => 'Current password is invalid',
    'changed_password_successfully'     => 'You have changed password successfully',
    'forgot_password_send_link_success' => 'We have e-mailed your password reset link!',
    'forgot_password_send_link_failed'  => 'We can\'t send email. Please try again!',
    'changed_status_successfully'       => 'You have changed status successfully',
    'invalid_permission'                => 'Your account doesn\'t this permission',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));