<?php
/**
 * Homework Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Homework extends Model
{
    protected static string $table = 'homework';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'instructor_id', 'class_stream_id', 'subject_id', 'title',
        'description', 'attachment', 'due_date', 'max_marks', 'status'
    ];

    public static function withDetails(int $homeworkId): ?array
    {
        $sql = "SELECT h.*, c.code as class_code, st.name as stream_name,
                       s.name as subject_name, s.code as subject_code,
                       u.first_name as instructor_first_name, u.last_name as instructor_last_name
                FROM homework h
                JOIN class_streams cs ON h.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON h.subject_id = s.id
                JOIN instructors i ON h.instructor_id = i.id
                JOIN users u ON i.user_id = u.id
                WHERE h.id = ?";
        return self::rawOne($sql, [$homeworkId]);
    }

    public static function byInstructor(int $instructorId): array
    {
        $sql = "SELECT h.*, c.code as class_code, st.name as stream_name,
                       s.name as subject_name
                FROM homework h
                JOIN class_streams cs ON h.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON h.subject_id = s.id
                WHERE h.instructor_id = ?
                ORDER BY h.due_date DESC";
        return self::raw($sql, [$instructorId]);
    }

    public static function byClassStream(int $classStreamId): array
    {
        $sql = "SELECT h.*, s.name as subject_name, s.code as subject_code,
                       u.first_name as instructor_first_name, u.last_name as instructor_last_name
                FROM homework h
                JOIN subjects s ON h.subject_id = s.id
                JOIN instructors i ON h.instructor_id = i.id
                JOIN users u ON i.user_id = u.id
                WHERE h.class_stream_id = ? AND h.status = 'active'
                ORDER BY h.due_date DESC";
        return self::raw($sql, [$classStreamId]);
    }

    public static function getSubmissionCount(int $homeworkId): int
    {
        $sql = "SELECT COUNT(*) as count FROM homework_submissions WHERE homework_id = ?";
        $result = self::rawOne($sql, [$homeworkId]);
        return (int)($result['count'] ?? 0);
    }

    public static function isPastDue(array $homework): bool
    {
        return strtotime($homework['due_date']) < time();
    }
}
