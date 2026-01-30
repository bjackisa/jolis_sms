<?php
/**
 * Exam Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Exam extends Model
{
    protected static string $table = 'exams';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'exam_type_id', 'term_id', 'class_stream_id', 'subject_id', 'paper_id',
        'name', 'max_marks', 'exam_date', 'status', 'created_by'
    ];

    public static function withDetails(int $examId): ?array
    {
        $sql = "SELECT e.*, et.name as exam_type_name, et.code as exam_type_code,
                       t.name as term_name, t.term_number,
                       c.name as class_name, c.code as class_code,
                       st.name as stream_name, s.name as subject_name, s.code as subject_code,
                       sp.name as paper_name, sp.paper_number,
                       ay.name as academic_year
                FROM exams e
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN terms t ON e.term_id = t.id
                JOIN class_streams cs ON e.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON e.subject_id = s.id
                JOIN academic_years ay ON cs.academic_year_id = ay.id
                LEFT JOIN subject_papers sp ON e.paper_id = sp.id
                WHERE e.id = ?";
        return self::rawOne($sql, [$examId]);
    }

    public static function allWithDetails(array $filters = []): array
    {
        $sql = "SELECT e.*, et.name as exam_type_name, et.code as exam_type_code,
                       t.name as term_name, c.code as class_code, st.name as stream_name,
                       s.name as subject_name, s.code as subject_code
                FROM exams e
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN terms t ON e.term_id = t.id
                JOIN class_streams cs ON e.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON e.subject_id = s.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['term_id'])) {
            $sql .= " AND e.term_id = ?";
            $params[] = $filters['term_id'];
        }
        
        if (!empty($filters['class_stream_id'])) {
            $sql .= " AND e.class_stream_id = ?";
            $params[] = $filters['class_stream_id'];
        }
        
        if (!empty($filters['subject_id'])) {
            $sql .= " AND e.subject_id = ?";
            $params[] = $filters['subject_id'];
        }
        
        if (!empty($filters['exam_type_id'])) {
            $sql .= " AND e.exam_type_id = ?";
            $params[] = $filters['exam_type_id'];
        }
        
        $sql .= " ORDER BY e.exam_date DESC, e.created_at DESC";
        return self::raw($sql, $params);
    }

    public static function getResultsCount(int $examId): int
    {
        $sql = "SELECT COUNT(*) as count FROM exam_results WHERE exam_id = ?";
        $result = self::rawOne($sql, [$examId]);
        return (int)($result['count'] ?? 0);
    }

    public static function byInstructor(int $instructorId, int $termId = null): array
    {
        $sql = "SELECT DISTINCT e.*, et.name as exam_type_name, et.code as exam_type_code,
                       c.code as class_code, st.name as stream_name,
                       s.name as subject_name
                FROM exams e
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN class_streams cs ON e.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON e.subject_id = s.id
                JOIN instructor_subjects ins ON ins.subject_id = e.subject_id 
                    AND ins.class_stream_id = e.class_stream_id
                JOIN instructors i ON ins.instructor_id = i.id
                WHERE i.id = ?";
        
        $params = [$instructorId];
        
        if ($termId) {
            $sql .= " AND e.term_id = ?";
            $params[] = $termId;
        }
        
        $sql .= " ORDER BY e.exam_date DESC";
        return self::raw($sql, $params);
    }
}
