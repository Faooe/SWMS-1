<?php

return [

    'Free' => [

        'label' => 'Free',

        'max_employee' => 5,

    ],

    'Premium Go' => [

        'label' => 'Premium Go',

        'max_employee' => 200,

        /*
        |----------------------------------------------------------------
        | Harga (Rupiah) -- SANDBOX, silakan sesuaikan dengan harga asli
        |----------------------------------------------------------------
        */

        'price' => [

            '1_month' => 99000,

            '3_months' => 269000,

            '12_months' => 999000,

        ],

    ],

    'Premium Plus' => [

        'label' => 'Premium Plus',

        'max_employee' => 500,

        'price' => [

            '1_month' => 199000,

            '3_months' => 549000,

            '12_months' => 1999000,

        ],

    ],

    'Premium Max' => [

        'label' => 'Premium Max',

        'max_employee' => 1000,

        'price' => [

            '1_month' => 349000,

            '3_months' => 949000,

            '12_months' => 3499000,

        ],

    ],

];
