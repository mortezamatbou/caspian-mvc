<?php

namespace Caspian;

class Tools {

    public static function trunc_route(string $route): string
    {
        $result = strip_tags($route);

        if (!$route || preg_match('/^\/+$/', strip_tags($route))) {
            return '/';
        }
        if (preg_match('/\/$/', $result)) {
            $result = substr($result, 0, strlen($result) - 1);
        }

        if (preg_match('/^\//', $result)) {
            $result = substr($result, 1, strlen($result) - 1);
        }

        return $result;
    }

    public static function trunc_uri(string $uri): array
    {
        $result = [
            'path' => '/',
            'query' => '',
            'segments' => []
        ];

        $parse = parse_url(strip_tags(trim($uri)));

        $result['path'] = self::trunc_route($parse['path']);
        if (isset($parse['query'])) {
            $result['query'] = trim($parse['query']);
        }

        if ($result['path'] != '/') {
            $result['segments'] = explode('/', $result['path']);
        }

        return $result;
    }
}
