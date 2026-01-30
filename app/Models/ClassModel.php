<?php
/**
 * ClassModel Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class ClassModel extends Model
{
    protected static string $table = 'classes';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['level_id', 'name', 'code', 'order_index', 'status'];

    public static function withLevel(int $classId): ?array
    {
        $sql = "SELECT c.*, l.name as level_name, l.code as level_code
                FROM classes c
                JOIN levels l ON c.level_id = l.id
                WHERE c.id = ?";
        return self::rawOne($sql, [$classId]);
    }

    public static function allWithLevels(): array
    {
        $sql = "SELECT c.*, l.name as level_name, l.code as level_code
                FROM classes c
                JOIN levels l ON c.level_id = l.id
                WHERE c.status = 'active'
                ORDER BY c.order_index";
        return self::raw($sql);
    }

    public static function oLevel(): array
    {
        $sql = "SELECT c.* FROM classes c
                JOIN levels l ON c.level_id = l.id
                WHERE l.code = 'O' AND c.status = 'active'
                ORDER BY c.order_index";
        return self::raw($sql);
    }

    public static function aLevel(): array
    {
        $sql = "SELECT c.* FROM classes c
                JOIN levels l ON c.level_id = l.id
                WHERE l.code = 'A' AND c.status = 'active'
                ORDER BY c.order_index";
        return self::raw($sql);
    }
}
