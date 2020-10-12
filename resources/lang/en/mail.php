<?php

$orginalTranslations = [
    'user' => [
        'subject_create_admin'         => 'Priceless-IT new Admin account created',
        'subject_account_verification' => 'Priceless-IT account verification',
        'subject_confirm_change_email' => 'Confirm request to change email address',
    ],
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));