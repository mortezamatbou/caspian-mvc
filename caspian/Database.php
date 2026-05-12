<?php

namespace Caspian;


class Database
{
    private static array $connections = [];
    private static array $config;
    private static string $default;

    public static function configure(array $config, string $default): void
    {
        self::$config = $config;
        self::$default = $default;
    }

    public static function connection(string $name): \Caspian\CI\DatabaseDriver
    {
        $name = $name && !empty($name) ? $name : self::$default;

        if (!isset(self::$connections[$name])) {
            self::connect($name);
        }

        return self::$connections[$name];
    }

    private static function connect(string $name): void
    {

        $db = self::$config[$name];
        self::$connections[$name] = CI_DB($db);
    }

    public static function getPDO(string $name = ''): \Caspian\CI\DatabaseQueryBuilder
    {
        return self::connection($name);
    }
}
