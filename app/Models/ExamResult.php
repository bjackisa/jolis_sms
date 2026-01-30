<?php
/**
 * ExamResult Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class ExamResult extends Model
{
    protected static string $table = 'exam_results';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'exam_id', 'student_id', 'marks_obtained', 'grade', 'points', 'comment', 'entered_by'
    ];

    public static function byExam(int $examId): array
    {
        $sql = "SELECT er.*, s.student_number, u.first_name, u.last_name
                FROM exam_results er
                JOIN students s ON er.student_id = s.id
                JOIN users u ON s.user_id = u.id
                WHERE er.exam_id = ?
                ORDER BY u.first_name, u.last_name";
        return self::raw($sql, [$examId]);
    }

    public static function byStudent(int $studentId, int $termId = null): array
    {
        $sql = "SELECT er.*, e.name as exam_name, e.max_marks, e.exam_date,
                       et.name as exam_type_name, et.code as exam_type_code,
                       s.name as subject_name, s.code as subject_code
                FROM exam_results er
                JOIN exams e ON er.exam_id = e.id
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN subjects s ON e.subject_id = s.id
                WHERE er.student_id = ?";
        
        $params = [$studentId];
        
        if ($termId) {
            $sql .= " AND e.term_id = ?";
            $params[] = $termId;
        }
        
        $sql .= " ORDER BY e.exam_date DESC";
        return self::raw($sql, $params);
    }

    public static function getOrCreate(int $examId, int $studentId): array
    {
        $existing = self::rawOne(
            "SELECT * FROM exam_results WHERE exam_id = ? AND student_id = ?",
            [$examId, $studentId]
        );
        
        if ($existing) {
            return $existing;
        }
        
        $id = self::create([
            'exam_id' => $examId,
            'student_id' => $studentId
        ]);
        
        return self::find($id);
    }

    public static function updateOrCreate(int $examId, int $studentId, array $data): int
    {
        $existing = self::rawOne(
            "SELECT id FROM exam_results WHERE exam_id = ? AND student_id = ?",
            [$examId, $studentId]
        );
        
        if ($existing) {
            self::update($existing['id'], $data);
            return $existing['id'];
        }
        
        $data['exam_id'] = $examId;
        $data['student_id'] = $studentId;
        return self::create($data);
    }

    public static function classAverage(int $examId): float
    {
        $sql = "SELECT AVG(marks_obtained) as average FROM exam_results WHERE exam_id = ?";
        $result = self::rawOne($sql, [$examId]);
        return round((float)($result['average'] ?? 0), 2);
    }

    public static function classHighest(int $examId): float
    {
        $sql = "SELECT MAX(marks_obtained) as highest FROM exam_results WHERE exam_id = ?";
        $result = self::rawOne($sql, [$examId]);
        return (float)($result['highest'] ?? 0);
    }

    public static function classLowest(int $examId): float
    {
        $sql = "SELECT MIN(marks_obtained) as lowest FROM exam_results WHERE exam_id = ?";
        $result = self::rawOne($sql, [$examId]);
        return (float)($result['lowest'] ?? 0);
    }
}
