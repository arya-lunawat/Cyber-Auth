<?php

function env($key, $default = null)
{
    static $env = null;

    if ($env === null) {

        $env = [];

        $path = dirname(__DIR__, 2) . '/.env';

        if (file_exists($path)) {

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {

                if (str_starts_with(trim($line), '#')) {
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