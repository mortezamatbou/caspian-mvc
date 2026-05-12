<?php

namespace Caspian;

use PDO;

class Registry
{
    private static array $container = [];

    public static function set(string $key, $value): void
    {
        self::$container[$key] = $value;
    }

    public static function get(string $key)
    {
        return self::$container[$key] ?? null;
    }

    public static function has(string $key): bool
    {
        return isset(self::$container[$key]);
    }
}
