<?php

return [
    'pdo'    => [
        'driver'           => 'mysql',
        'host'             => 'localhost',
        'db_name'          => 'bug',
        'username'         => 'root',
        'db_user_password' => '12345',
        'default_fetch'    => PDO::FETCH_OBJ,
    ],
    'mysqli' => [
        'driver'           => 'mysql',
        'host'             => 'localhost',
        'db_name'          => 'bug',
        'username'         => 'root',
        'db_user_password' => '12345',
        'default_fetch'    => MYSQLI_ASSOC,
    ],
];