<?php

$orginalTranslations = [
    'Administrator'         => 'Administrator',
    'User'                  => 'User',
    'backoffice_role_title' => 'Roles',
    'client_role_title'     => 'Client roles',
    'add_role'              => 'Add role',
    'edit_role'             => 'Edit role',
    'detail_role'           => 'Detail role',
    'are_you_sure_delete'   => 'Are you sure to delete this role?',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));