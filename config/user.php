<?php

return [
    'users_allowed_login' => [
        \App\Enums\UserType::ThirdPartyCashierEmployee->value,
        \App\Enums\UserType::RUEmployee->value,
        \App\Enums\UserType::CSEmployee->value
    ],

    'users_auth_iduffs' => [
        \App\Enums\UserType::Employee->value,
        \App\Enums\UserType::Student->value,
<<<<<<< Updated upstream
        \App\Enums\UserType::RUEmployee->value
=======
        \App\Enums\UserType::RUEmployee->value,
        \App\Enums\UserType::CSEmployee->value
    ],

    'users_without_iduffs' => [
        \App\Enums\UserType::ThirdPartyCashierEmployee->value
>>>>>>> Stashed changes
    ]
];
