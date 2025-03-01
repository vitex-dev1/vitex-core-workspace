<?php

$orginalTranslations = [

    /*
    |--------------------------------------------------------------------------
    | Default Simple CMS
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'dashboard' => 'Dashboard',
    'post'      => 'Post',
    'category'  => 'Category',
    'tag'       => 'Tag',
    'page'      => 'Page',
    'menu'      => 'Menu Manager',
    'logout'    => 'Logout',
    'theme'     => 'Theme Manager',
    'widget'    => 'Widget Manager',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));