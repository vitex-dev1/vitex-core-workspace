<?php

$orginalTranslations = [
    'created_successfully' => 'Workspace has been created',
    'updated_successfully' => 'Workspace has been updated',
    'deleted_successfully' => 'Workspace has been deleted',
    'not_found'            => 'Workspace not found',

    'sales_number'        => 'Sales number',
    'fulfillment_method'  => 'Shipment fulfillment',
    'api_key'             => 'API-key',
    'secret_key'          => 'Secret',
    'ftps_user'           => 'FTPS user',
    'ftps_password'       => 'FTPS password',

    /* Options */
    'fulfillment_options' => [
        'FBR' => 'By seller',
        'FBB' => 'By bol.com',
    ],

    'add_workspace'       => 'Add workspace',
    'edit_workspace'      => 'Edit workspace',
    'detail_workspace'    => 'Detail workspace',
    'are_you_sure_delete' => 'Are you sure to delete this workspace?',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));
