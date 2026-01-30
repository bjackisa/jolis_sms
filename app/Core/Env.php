<?php
namespace App\Core;

class Env
{
    protected static array $variables = [];

    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            $value = trim($value, '"\'');

            self::$variables[$name] = $value;
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }

    public static function get(string $key, $default = null)
    {
        if (isset(self::$variables[$key])) {
            return self::parseValue(self::$variables[$key]);
        }

        $value = getenv($key);
        if ($value !== false) {
            return self::parseValue($value);
        }

        return $default;
    }

    protected static function parseValue($value)
    {
        if (strtolower($value) === 'true') return true;
        if (strtolower($value) === 'false') return false;
        if (strtolower($value) === 'null') return null;
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float)$value : (int)$value;
        }
        return $value;
    }
}
