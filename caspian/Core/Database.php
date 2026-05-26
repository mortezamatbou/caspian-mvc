<?php

namespace Caspian\Core;

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

    public static function connection(string $name): \Caspian\River\DatabaseDriver
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

        // $dsn = "{$db['driver']}:host={$db['hostname']};port={$db['port']};dbname={$db['database']};charset={$db['char_set']}";
        // $options = [
        //     \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        //     \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        // ];
        // if (isset($db['persistent']) && $db['persistent'] === true) {
        //     $options[\PDO::ATTR_PERSISTENT] = true;
        //     error_log("Using persistent connection for: {$name}");
        // }
        // self::$connections[$name] = new \PDO($dsn, $db['username'], $db['password'], $options);

        self::$connections[$name] = CI_DB($db);
    }

    public static function getPDO(string $name = ''): \Caspian\River\DatabaseQueryBuilder
    {
        return self::connection($name);
    }
}
