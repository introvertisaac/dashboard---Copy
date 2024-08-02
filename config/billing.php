<?php

return [
    'national_id' => env('ID_CHECK_COST', 5),
    'brs' => env('BRS_CHECK_COST', 650),
    'alien_id' => env('BRS_CHECK_COST', 10),
    'kra' => env('KRA_PIN_CHECK_COST', 1),
    'dl' => env('DL_CHECK_COST', 25),
    'plate' => env('PLATE_CHECK_COST', 450),
    'phone' => env('PHONE_CHECK_COST', 2),
    'bank' => env('BANK_CHECK_COST', 1),
    'collateral' => env('COLLATERAL_CHECK_COST', 400),


    'free_wallet_amount' => env('TEST_CUSTOMER_AMOUNT', 3000),

    'national_id_wholesale' => env('ID_CHECK_WHOLESALE', 4),
    'brs_wholesale' => env('BRS_CHECK_WHOLESALE', 400),
    'alien_id_wholesale' => env('ALIENID_CHECK_WHOLESALE', 10),
    'kra_pin_wholesale' => env('KRA_PIN_CHECK_WHOLESALE', 2),
    'dl_wholesale' => env('DL_CHECK_WHOLESALE', 25),
    'plate_wholesale' => env('PLATE_CHECK_WHOLESALE', 450),
    'phone_wholesale' => env('PHONE_CHECK_WHOLESALE', 2),
    'bank_wholesale' => env('BANK_CHECK_WHOLESALE', 1),
    'collateral_wholesale' => env('BANK_CHECK_WHOLESALE', 400),


    'services' => [

        'national_id' => ['input_label' => 'National ID Number', 'label' => 'National ID'],
        'alien_id' => ['input_label' => 'Alien ID Number', 'label' => 'Alien ID'],
        'plate' => ['input_label' => 'Vehicle Number Plate', 'label' => 'Vehicle Plate'],
        'dl' => ['input_label' => 'Driving License', 'label' => 'Driving License'],
        'kra' => ['input_label' => 'KRA Pin', 'label' => 'KRA Pin'],
        'brs' => ['input_label' => 'Company Registration Number', 'label' => 'Business'],
        'collateral' => ['input_label' => 'Collateral Identifier eg. Chassis Number', 'label' => 'Collateral'],
        'bank' => ['input_label' => 'Bank Account Number', 'label' => 'Bank Account']

    ],


    'banks' => [
        1 => "KCB",
        2 => "Standard Chartered Bank",
        3 => "Absa Bank",
        4 => "Bank of India",
        5 => "Bank of Baroda",
        6 => "NCBA",
        7 => "Prime Bank",
        8 => "Co-operative Bank",
        9 => "National Bank",
        10 => "M-Oriental",
        11 => "Citi Bank",
        12 => "Habib Bank AG Zurich",
        13 => "Middle East Bank",
        14 => "Bank of Africa",
        15 => "Consolidated Bank",
        16 => "Credit Bank",
        17 => "Access Bank",
        18 => "Stanbic Bank",
        19 => "ABC Bank",
        20 => "Eco Bank",
        21 => "SPIRE Bank",
        22 => "Paramount",
        23 => "Kingdom Bank",
        24 => "Guaranty Trust Bank (GT Bank)",
        25 => "Victoria Bank",
        26 => "Guardian Bank",
        27 => "I&M Bank",
        28 => "Development Bank",
        29 => "SBM",
        30 => "Housing finance",
        31 => "Diamond Trust Bank (DTB)",
        32 => "Mayfair Bank",
        33 => "Sidian Bank",
        34 => "Equity Bank",
        35 => "Family Bank",
        36 => "Gulf African Bank",
        37 => "First Community Bank",
        38 => "DIB Bank",
        39 => "UBA Bank",
        40 => "KWFT",
        41 => "Faulu Bank",
        42 => "Post Bank"
    ],

];
