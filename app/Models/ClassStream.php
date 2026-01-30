<?php
/**
 * ClassStream Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class ClassStream extends Model
{
    protected static string $table = 'class_streams';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['class_id', 'stream_id', 'academic_year_id', 'capacity', 'status'];

    public static function withDetails(int $classStreamId): ?array
    {
        $sql = "SELECT cs.*, c.name as class_name, c.code as class_code, 
                       s.name as stream_name, ay.name as academic_year,
                       l.name as level_name, l.code as level_code
                FROM class_streams cs
                JOIN classes c ON cs.class_id = c.id
                JOIN streams s ON cs.stream_id = s.id
                JOIN academic_years ay ON cs.academic_year_id = ay.id
                JOIN levels l ON c.level_id = l.id
                WHERE cs.id = ?";
        return self::rawOne($sql, [$classStreamId]);
    }

    public static function allWithDetails(int $academicYearId = null): array
    {
        $sql = "SELECT cs.*, c.name as class_name, c.code as class_code, 
                       s.name as stream_name, ay.name as academic_year,
                       l.name as level_name, l.code as level_code,
                       c.order_index
                FROM class_streams cs
                JOIN classes c ON cs.class_id = c.id
                JOIN streams s ON cs.stream_id = s.id
                JOIN academic_years ay ON cs.academic_year_id = ay.id
                JOIN levels l ON c.level_id = l.id
                WHERE cs.status = 'active'";
        
        $params = [];
        if ($academicYearId) {
            $sql .= " AND cs.academic_year_id = ?";
            $params[] = $academicYearId;
        }
        
        $sql .= " ORDER BY c.order_index, s.name";
        return self::raw($sql, $params);
    }

    public static function getStudentCount(int $classStreamId): int
    {
        $sql = "SELECT COUNT(*) as count FROM student_enrollments 
                WHERE class_stream_id = ? AND status = 'active'";
        $result = self::rawOne($sql, [$classStreamId]);
        return (int)($result['count'] ?? 0);
    }

    public static function getDisplayName(array $classStream): string
    {
        return $classStream['class_code'] . ' ' . $classStream['stream_name'];
    }
}
