<?php

namespace Caspian\Core;


class Registry
{
    private static array $container = [];

    public static function set(string $key, $value, bool $force = false): void
    {
        if (self::has($key) && !$force) {
            return;
        }
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
