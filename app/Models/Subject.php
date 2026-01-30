<?php
/**
 * Subject Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Subject extends Model
{
    protected static string $table = 'subjects';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'category_id', 'level_id', 'name', 'code', 'paper_count', 'is_compulsory', 'status'
    ];

    public static function withDetails(int $subjectId): ?array
    {
        $sql = "SELECT s.*, l.name as level_name, l.code as level_code,
                       sc.name as category_name
                FROM subjects s
                JOIN levels l ON s.level_id = l.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE s.id = ?";
        return self::rawOne($sql, [$subjectId]);
    }

    public static function allWithDetails(): array
    {
        $sql = "SELECT s.*, l.name as level_name, l.code as level_code,
                       sc.name as category_name
                FROM subjects s
                JOIN levels l ON s.level_id = l.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE s.status = 'active'
                ORDER BY l.code, s.name";
        return self::raw($sql);
    }

    public static function byLevel(string $levelCode): array
    {
        $sql = "SELECT s.*, sc.name as category_name
                FROM subjects s
                JOIN levels l ON s.level_id = l.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE l.code = ? AND s.status = 'active'
                ORDER BY s.name";
        return self::raw($sql, [$levelCode]);
    }

    public static function getPapers(int $subjectId): array
    {
        $sql = "SELECT * FROM subject_papers WHERE subject_id = ? ORDER BY paper_number";
        return self::raw($sql, [$subjectId]);
    }

    public static function compulsory(int $levelId): array
    {
        return self::whereMultiple(['level_id' => $levelId, 'is_compulsory' => 1]);
    }
}
