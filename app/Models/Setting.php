<?php
/**
 * Setting Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected static string $table = 'settings';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['key', 'value', 'type'];

    private static array $cache = [];

    public static function get(string $key, $default = null)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $setting = self::rawOne('SELECT * FROM settings WHERE `key` = ? LIMIT 1', [$key]);
        
        if (!$setting) {
            return $default;
        }

        $value = self::castValue($setting['value'], $setting['type']);
        self::$cache[$key] = $value;
        
        return $value;
    }

    public static function set(string $key, $value, string $type = 'string'): void
    {
        $existing = self::rawOne('SELECT * FROM settings WHERE `key` = ? LIMIT 1', [$key]);
        
        if ($existing) {
            self::update($existing['id'], ['value' => (string)$value, 'type' => $type]);
        } else {
            self::create(['key' => $key, 'value' => (string)$value, 'type' => $type]);
        }

        self::$cache[$key] = self::castValue($value, $type);
    }

    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public static function getAll(): array
    {
        $settings = self::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = self::castValue($setting['value'], $setting['type']);
        }
        
        return $result;
    }

    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
