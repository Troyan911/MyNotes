<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?? null,
        'name' => getenv('DB_NAME') ?? null,
        'user' => getenv('DB_USER') ?? null,
        'pass' => getenv('DB_PASS') ?? null
    ]
];

