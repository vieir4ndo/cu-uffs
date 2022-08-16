<?php

return [
    'users_allowed_login' => [
        \App\Enums\UserType::ThirdPartyCashierEmployee->value,
        \App\Enums\UserType::RUEmployee->value,
        \App\Enums\UserType::CSManager->value
    ],

    'users_auth_iduffs' => [
        \App\Enums\UserType::Employee->value,
        \App\Enums\UserType::Student->value,
        \App\Enums\UserType::RUEmployee->value,
        \App\Enums\UserType::CSManager->value
    ],

    'users_without_iduffs' => [
        \App\Enums\UserType::ThirdPartyCashierEmployee->value
    ]
];
