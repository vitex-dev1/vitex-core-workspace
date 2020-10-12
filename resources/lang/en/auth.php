<?php

$orginalTranslations = [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed'   => 'The email address or password you have entered was not correct. Please try again!',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'banned'   => 'Your account has been deactivated by the administrators. Please contact them for more details.',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));
