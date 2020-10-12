<?php

$orginalTranslations = [
    'user' => [
        'User Management' => 'User Management',
        'List'            => 'List',
        'View'            => 'View',
        'Add New'         => 'Add',
        'Edit'            => 'Edit',
        'Delete'          => 'Delete',
        'Ban'             => 'Ban',
    ],
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));