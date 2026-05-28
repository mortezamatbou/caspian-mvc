<?php

namespace Caspian\Http;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $cookie;
    private array $files;
    private array $headers;
    private ?array $input = null;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->headers = $this->parse_headers();
    }

    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function post(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function request(?string $key = null, $default = null)
    {
        $request = array_merge($this->get, $this->post);
        if ($key === null) {
            return $request;
        }
        return $request[$key] ?? $default;
    }

    public function cookie(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->cookie;
        }
        return $this->cookie[$key] ?? $default;
    }

    public function server(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->server;
        }
        $key = strtoupper($key);
        return $this->server[$key] ?? $default;
    }

    public function header(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->headers;
        }
        $key = str_replace('-', '_', strtolower(trim($key)));
        return $this->headers[$key] ?? $default;
    }

    public function file(?string $key = null)
    {
        if ($key === null) {
            return $this->files;
        }
        return $this->files[$key] ?? null;
    }

    public function body(?string $key = null, $default = null)
    {
        if ($this->input === null) {
            $this->parse_input();
        }

        if ($key === null) {
            return $this->input;
        }

        return $this->input[$key] ?? $default;
    }


    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->body());
    }

    public function has(string $key): bool
    {
        return isset($this->get[$key]) || isset($this->post[$key]) || isset($this->body()[$key]);
    }

    public function only(array $keys): array
    {
        $all = $this->all();
        return array_intersect_key($all, array_flip($keys));
    }

    public function except(array $keys): array
    {
        $all = $this->all();
        return array_diff_key($all, array_flip($keys));
    }

    public function method(): string
    {
        return strtoupper($this->server('REQUEST_METHOD', 'GET'));
    }

    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    public function isAjax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }

    public function is_secure(): bool
    {
        return $this->server('HTTPS') === 'on' || $this->server('HTTP_X_FORWARDED_PROTO') === 'https';
    }

    public function ip(): string
    {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if ($ip = $this->server($header)) {
                $ips = explode(',', $ip);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }


    public function full_url(): string
    {
        $protocol = $this->is_secure() ? 'https' : 'http';
        $host = $this->server('HTTP_HOST');
        $uri = $this->server('REQUEST_URI');

        return $protocol . '://' . $host . $uri;
    }


    public function path(): string
    {
        $uri = parse_url($this->server('REQUEST_URI', ''), PHP_URL_PATH);
        return $uri ?? '/';
    }


    public function query(): array
    {
        parse_str($this->server('QUERY_STRING', ''), $query);
        return $query;
    }


    public function bearer_token(): ?string
    {
        $header = $this->header('Authorization');

        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }


    private function parse_headers(): array
    {
        $headers = [];

        foreach ($this->server as $key => $value) {
            $header = strtolower(trim($key));
            $headers[$header] = $value;
        }

        return $headers;
    }


    private function parse_input(): void
    {
        $content_type = $this->header('Content-Type');

        if (!$content_type) {
            return;
        }

        $content_type = strtolower(trim($content_type));

        if ($content_type == 'application/json') {
            $input = file_get_contents('php://input');
            $this->input = json_decode($input, true) ?? [];
            return;
        }

        if ($content_type == 'application/x-www-form-urlencoded') {
            parse_str(file_get_contents('php://input'), $this->input);
            return;
        }

        $this->input = [];
    }

    public function all_get(): array
    {
        return $this->get;
    }

    public function all_post(): array
    {
        return $this->post;
    }

    public function get_file(string $key)
    {
        if (!isset($this->files[$key])) {
            return null;
        }

        $file = $this->files[$key];

        if (is_array($file['name'])) {
            $files = [];
            for ($i = 0; $i < count($file['name']); $i++) {
                if ($file['error'][$i] === UPLOAD_ERR_OK) {
                    $files[] = [
                        'name' => $file['name'][$i],
                        'type' => $file['type'][$i],
                        'tmp_name' => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                        'size' => $file['size'][$i]
                    ];
                }
            }
            return $files;
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            return $file;
        }

        return null;
    }
}
