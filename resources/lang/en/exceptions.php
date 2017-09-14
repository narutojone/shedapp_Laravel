<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in Exceptions thrown throughout the system.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */


    'employees' => [
	    'create_error'          => 'There was a problem creating this employee. Please try again.',
        'update_error'          => 'There was a problem updating this employee. Please try again.',
	    'delete_error'          => 'There was a problem deleting this user. Please try again.'
    ],
    'orders' => [
        'document' => [
            'generate' => [
                'order_status_is_not_acceptable' => 'Order status shouldn\'t be ":wrong_status".',
                'complete_order_documents' => [
                    'driver_license_is_not_found' => 'Driver\'s license is not found.',
                    'signed_building_configuration_is_not_found' => 'Signed building configuration is not found.'
                ]
            ],
            'esignature' => [
                'customer_email_is_required' => 'Customer Email is Required',
                'complete_order_documents_is_not_found' => 'Complete Order Documents is not found.',
                'status_is_signature_pending' => 'Order is already waiting for signature.',
                'status_is_signed' => 'Order is already signed.',
                'unable_to_esign' => 'Unable to make esignature request.',
                'download_url_is_emtpy' => 'Download url is empty.',
            ]
        ],
        'is_not_allowed_to_update' => "Changes are not allowed on this order. If you require assistance please contact :contact_name."
    ],
    'customers' => [
        'unable_to_save_customer_from_order' => 'Unable to save customer data.'
    ]

];
