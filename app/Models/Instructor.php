<?php
/**
 * Instructor Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Instructor extends Model
{
    protected static string $table = 'instructors';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'user_id', 'employee_id', 'qualification', 'specialization', 'date_joined'
    ];

    public static function withUser(int $instructorId): ?array
    {
        $sql = "SELECT i.*, u.email, u.first_name, u.last_name, u.phone, u.avatar, u.status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE i.id = ?";
        return self::rawOne($sql, [$instructorId]);
    }

    public static function allWithUsers(): array
    {
        $sql = "SELECT i.*, u.email, u.first_name, u.last_name, u.phone, u.avatar, u.status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE u.status = 'active'
                ORDER BY u.first_name, u.last_name";
        return self::raw($sql);
    }

    public static function findByUserId(int $userId): ?array
    {
        return self::findBy('user_id', $userId);
    }

    public static function getSubjects(int $instructorId, int $academicYearId): array
    {
        $sql = "SELECT DISTINCT s.*, sc.name as category_name
                FROM instructor_subjects ins
                JOIN subjects s ON ins.subject_id = s.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE ins.instructor_id = ? AND ins.academic_year_id = ?
                ORDER BY s.name";
        return self::raw($sql, [$instructorId, $academicYearId]);
    }

    public static function getClasses(int $instructorId, int $academicYearId): array
    {
        $sql = "SELECT DISTINCT cs.id as class_stream_id, c.name as class_name, 
                       c.code as class_code, st.name as stream_name
                FROM instructor_subjects ins
                JOIN class_streams cs ON ins.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                WHERE ins.instructor_id = ? AND ins.academic_year_id = ?
                ORDER BY c.order_index, st.name";
        return self::raw($sql, [$instructorId, $academicYearId]);
    }
}
