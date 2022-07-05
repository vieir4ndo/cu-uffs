<?php

return [
    'users_allowed_login' => [
        \App\Enums\UserType::ThirdPartyEmployee->value,
        \App\Enums\UserType::RUEmployee->value
    ],

    'users_auth_iduffs' => [
        \App\Enums\UserType::default->value,
        \App\Enums\UserType::RUEmployee->value
    ],

    'users_auth_locally' => [
        \App\Enums\UserType::ThirdPartyEmployee->value,
        \App\Enums\UserType::ThirdPartyCashierEmployee->value,
    ],
];
