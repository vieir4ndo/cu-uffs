<?php

return [
    'users_allowed_login' => [
        \App\Enums\UserType::ThirdPartyEmployee->value,
        \App\Enums\UserType::RUEmployee->value
    ],

    'users_auth_iduffs' => [
        \App\Enums\UserType::Employee->value,
        \App\Enums\UserType::Student->value,
        \App\Enums\UserType::RUEmployee->value
    ],
];
