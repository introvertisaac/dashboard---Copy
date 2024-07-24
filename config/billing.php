<?php

return [
    'id_check' => env('ID_CHECK_COST', 5),
    'brs_check' => env('BRS_CHECK_COST', 650),
    'alien_id_check' => env('BRS_CHECK_COST', 10),
    'kra_pin_check' => env('KRA_PIN_CHECK_COST', 1),
    'dl_check' => env('DL_CHECK_COST', 25),
    'plate_check' => env('PLATE_CHECK_COST', 450),
    'phone_check' => env('PHONE_CHECK_COST', 2),
    'bank_check' => env('BANK_CHECK_COST', 1),


    'free_wallet_amount' => env('TEST_CUSTOMER_AMOUNT', 1000),

    'id_check_wholesale' => env('ID_CHECK_WHOLESALE', 4),
    'brs_check_wholesale' => env('BRS_CHECK_WHOLESALE', 400),
    'alien_id_check_wholesale' => env('ALIENID_CHECK_WHOLESALE', 10),
    'kra_pin_check_wholesale' => env('KRA_PIN_CHECK_WHOLESALE', 2),
    'dl_check_wholesale' => env('DL_CHECK_WHOLESALE', 25),
    'plate_check_wholesale' => env('PLATE_CHECK_WHOLESALE', 450),
    'phone_check_wholesale' => env('PHONE_CHECK_WHOLESALE', 2),
    'bank_check_wholesale' => env('BANK_CHECK_WHOLESALE', 1),


    'services' => [
        'id' => ['name' => 'ID', 'key' => 'id'],

        'alien_id' => ['name' => 'Alien ID'],

        'brs' => ['name' => 'BRS'],

        'kra_pin' => ['name' => 'KRA Pin'],

        'plate' => ['name' => 'Vehicle Plate'],

        'bank_check' => ['name' => 'Bank Account']

    ],


];
