<?php
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception("The .env file does not exist.");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env = [];

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }

        return $env;
    }
}

$env = loadEnv(__DIR__ . '/.env');

if (!defined('MAILGUN_API_KEY')) {
    define('MAILGUN_API_KEY', $env['MAILGUN_API_KEY']);
}

if (!defined('MAILGUN_DOMAIN')) {
    define('MAILGUN_DOMAIN', $env['MAILGUN_DOMAIN']);
}

require_once __DIR__ . '/db_data/database.php';
?>
