<?php
/**
 * Student Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Student extends Model
{
    protected static string $table = 'students';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'user_id', 'student_number', 'admission_date', 'date_of_birth',
        'gender', 'address', 'guardian_name', 'guardian_phone', 'guardian_email'
    ];

    public static function withUser(int $studentId): ?array
    {
        $sql = "SELECT s.*, u.email, u.first_name, u.last_name, u.phone, u.avatar, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id
                WHERE s.id = ?";
        return self::rawOne($sql, [$studentId]);
    }

    public static function allWithUsers(): array
    {
        $sql = "SELECT s.*, u.email, u.first_name, u.last_name, u.phone, u.avatar, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id
                WHERE u.status = 'active'
                ORDER BY u.first_name, u.last_name";
        return self::raw($sql);
    }

    public static function findByUserId(int $userId): ?array
    {
        return self::findBy('user_id', $userId);
    }

    public static function findByStudentNumber(string $studentNumber): ?array
    {
        return self::findBy('student_number', $studentNumber);
    }

    public static function getCurrentEnrollment(int $studentId): ?array
    {
        $sql = "SELECT se.*, cs.id as class_stream_id, c.name as class_name, 
                       c.code as class_code, st.name as stream_name,
                       ay.name as academic_year, t.name as term_name
                FROM student_enrollments se
                JOIN class_streams cs ON se.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN academic_years ay ON se.academic_year_id = ay.id
                JOIN terms t ON se.term_id = t.id
                WHERE se.student_id = ? AND se.status = 'active'
                ORDER BY se.id DESC LIMIT 1";
        return self::rawOne($sql, [$studentId]);
    }

    public static function getByClassStream(int $classStreamId): array
    {
        $sql = "SELECT s.*, u.email, u.first_name, u.last_name, u.phone, u.avatar
                FROM students s
                JOIN users u ON s.user_id = u.id
                JOIN student_enrollments se ON s.id = se.student_id
                WHERE se.class_stream_id = ? AND se.status = 'active' AND u.status = 'active'
                ORDER BY u.first_name, u.last_name";
        return self::raw($sql, [$classStreamId]);
    }

    public static function getSubjects(int $studentId, int $academicYearId): array
    {
        $sql = "SELECT s.*, sc.name as category_name
                FROM student_subjects ss
                JOIN subjects s ON ss.subject_id = s.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE ss.student_id = ? AND ss.academic_year_id = ?
                ORDER BY s.name";
        return self::raw($sql, [$studentId, $academicYearId]);
    }
}
