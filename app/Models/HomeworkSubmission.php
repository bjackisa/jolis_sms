<?php
/**
 * HomeworkSubmission Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class HomeworkSubmission extends Model
{
    protected static string $table = 'homework_submissions';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'homework_id', 'student_id', 'submission_text', 'attachment',
        'marks_obtained', 'feedback', 'submitted_at', 'graded_at', 'graded_by', 'status'
    ];

    public static function byHomework(int $homeworkId): array
    {
        $sql = "SELECT hs.*, s.student_number, u.first_name, u.last_name
                FROM homework_submissions hs
                JOIN students s ON hs.student_id = s.id
                JOIN users u ON s.user_id = u.id
                WHERE hs.homework_id = ?
                ORDER BY hs.submitted_at DESC";
        return self::raw($sql, [$homeworkId]);
    }

    public static function byStudent(int $studentId): array
    {
        $sql = "SELECT hs.*, h.title as homework_title, h.due_date, h.max_marks,
                       s.name as subject_name
                FROM homework_submissions hs
                JOIN homework h ON hs.homework_id = h.id
                JOIN subjects s ON h.subject_id = s.id
                WHERE hs.student_id = ?
                ORDER BY hs.submitted_at DESC";
        return self::raw($sql, [$studentId]);
    }

    public static function hasSubmitted(int $homeworkId, int $studentId): bool
    {
        $sql = "SELECT id FROM homework_submissions WHERE homework_id = ? AND student_id = ?";
        $result = self::rawOne($sql, [$homeworkId, $studentId]);
        return $result !== null;
    }

    public static function getSubmission(int $homeworkId, int $studentId): ?array
    {
        $sql = "SELECT * FROM homework_submissions WHERE homework_id = ? AND student_id = ?";
        return self::rawOne($sql, [$homeworkId, $studentId]);
    }
}
