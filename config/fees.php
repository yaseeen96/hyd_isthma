<?php

return [
    'state_based_fees' => [
        'special_states' => [
            'Gujarat', 
            'Maharashtra', 
            'Telangana', 
            'Andhra Pradesh', 
            'Karnataka', 
            'Goa', 
            'Kerala', 
            'Tamil Nadu'
        ],
        'special_fee' => 3000,
        'default_fee' => 2000,
    ],

    'mehram_fees' => [
        'male' => 600,
        'female' => 600,
        'children_below_7' => 0,
        'children_above_7' => 600,
    ],
];
