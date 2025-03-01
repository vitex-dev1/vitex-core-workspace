<?php

$orginalTranslations = [
    'add_user'                      => 'Add user',
    'edit_user'                     => 'Edit user',
    'detail_user'                   => 'Detail user',
    'profile_title'                 => 'Profile',
    'change_profile'                => 'Change profile',
    'title_all'                     => 'Users',
    'title_all_admins'              => 'Admins',
    'title_detail'                  => 'User',
    'title_add'                     => 'Add new user',
    'title_add_admin'               => 'Add new admin',
    'title_add_user'                => 'Add new user',
    'title_edit'                    => 'Edit user',
    'title_edit_admin'              => 'Edit new admin',
    'title_edit_user'               => 'Edit new user',
    'label_name'                    => 'Name',
    'label_full_name'               => 'Full name',
    'label_role'                    => 'Role',
    'label_birthday'                => 'Date of birth',
    'label_gender'                  => 'Gender',
    'label_address'                 => 'Address',
    'label_phone'                   => 'Phone',
    'label_email'                   => 'Email',
    'label_description'             => 'Introduction',
    'label_verification'            => 'Verification',
    'label_created_at'              => 'Created at',
    'label_last_login'              => 'Last login at',
    'label_status_and_verification' => 'Status/Verification',
    'label_status'                  => 'Status',
    'label_timezone'                => 'Timezone',
    'label_authorization_token'     => 'Authorization token',
    'label_is_admin'                => 'Is admin?',
    'label_workspace'               => 'Workspace',

    'placeholder_workspace'        => 'Select workspace',
    'placeholder_role'             => 'Select role',
    'change_avatar'                => 'Change avatar',
    'edit_profile'                 => 'Edit profile',
    'change_password'              => 'Change password',
    'verified_successfully'        => 'Verified successfully.',
    'thanks_for_verifying_account' => '<p>Thanks for verifying your email address on this account.<br>
            We hope you would enjoy APP-IT.</p>',
    'changed_email_successfully'   => 'Changed email successfully.',
    'button_assign'                => 'Assign',
    'status'                       => 'Status',
    'postcode'                     => 'Postcode',
    'gsm_number'                   => 'GSM-number',
    'surname'                      => 'Surname',
    'first_name'                   => 'First name',
    'last_name'                    => 'Last name',
    'are_you_sure_delete'          => 'Are you sure to delete this user?',
    'are_you_sure_ban'             => 'Are you sure to ban this user?',
    'are_you_sure_unban'           => 'Are you sure to un-ban this user?',
    'email_changed_successfully'   => 'Your profile has updated successfully. Please check email to confirm new email.',
];

return \App\Helpers\Helper::getFileJsonLang($orginalTranslations, basename(__FILE__, '.json'));