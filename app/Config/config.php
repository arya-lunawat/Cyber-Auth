<?php

function env($key, $default = null)
{
    // Railway / Docker environment variables
    $value = getenv($key);

    if ($value !== false) {
        return $value;
    }

    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }

    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    // Local .env fallback
    static $env = null;

    if ($env === null) {

        $env = [];

        $path = dirname(__DIR__, 2) . '/.env';

        if (file_exists($path)) {

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {

                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                if (strpos($line, '=') !== false) {

                    [$k, $v] = explode('=', $line, 2);

                    $env[trim($k)] = trim($v);
                }
            }
        }
    }

    return $env[$key] ?? $default;
}