<?php

$orginalTranslations = [
    'dashboard'       => 'Dashboard',
    'role_manager'    => 'Roles manager',
    'role_backoffice' => 'Role manager',
    'role_client'     => 'Client role manager',
    'admin_manager'   => 'User manager',
    'workspaces'      => 'Workspaces',
    'platform'        => 'Platform',
    'lang_manager'    => 'Language manager',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));