<?php

use Caspian\Database;

$db = [
    'default' => [
        'dsn' => 'mysql:host=mariadb;dbname=musicdb',
        'hostname' => 'mariadb',
        'port' => 3306,
        'username' => 'musicuser',
        'password' => 'musicpass',
        'database' => 'musicdb',
        'dbdriver' => 'pdo',
        'dbprefix' => '',
        'pconnect' => TRUE,
        'db_debug' => 'development', // development, production
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    ]
];

Database::configure($db, 'default');
