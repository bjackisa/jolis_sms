<?php
/**
 * GradingScale Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class GradingScale extends Model
{
    protected static string $table = 'grading_scales';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['level_id', 'grade', 'min_marks', 'max_marks', 'points', 'comment'];

    public static function byLevel(int $levelId): array
    {
        $sql = "SELECT * FROM grading_scales WHERE level_id = ? ORDER BY min_marks DESC";
        return self::raw($sql, [$levelId]);
    }

    public static function byLevelCode(string $levelCode): array
    {
        $sql = "SELECT gs.* FROM grading_scales gs
                JOIN levels l ON gs.level_id = l.id
                WHERE l.code = ?
                ORDER BY gs.min_marks DESC";
        return self::raw($sql, [$levelCode]);
    }

    public static function getGrade(float $marks, int $levelId): array
    {
        $sql = "SELECT * FROM grading_scales 
                WHERE level_id = ? AND ? >= min_marks AND ? <= max_marks
                LIMIT 1";
        $result = self::rawOne($sql, [$levelId, $marks, $marks]);
        
        if (!$result) {
            return ['grade' => 'F9', 'points' => 9, 'comment' => 'Fail'];
        }
        
        return $result;
    }

    public static function calculateOLevelGrade(float $marks): array
    {
        $sql = "SELECT gs.* FROM grading_scales gs
                JOIN levels l ON gs.level_id = l.id
                WHERE l.code = 'O' AND ? >= gs.min_marks AND ? <= gs.max_marks
                LIMIT 1";
        $result = self::rawOne($sql, [$marks, $marks]);
        
        if (!$result) {
            return ['grade' => 'F9', 'points' => 9, 'comment' => 'Fail'];
        }
        
        return $result;
    }

    public static function calculateALevelGrade(float $marks): array
    {
        $sql = "SELECT gs.* FROM grading_scales gs
                JOIN levels l ON gs.level_id = l.id
                WHERE l.code = 'A' AND ? >= gs.min_marks AND ? <= gs.max_marks
                LIMIT 1";
        $result = self::rawOne($sql, [$marks, $marks]);
        
        if (!$result) {
            return ['grade' => 'F', 'points' => 0, 'comment' => 'Fail'];
        }
        
        return $result;
    }

    public static function calculateAggregate(array $points): int
    {
        sort($points);
        $bestEight = array_slice($points, 0, 8);
        return array_sum($bestEight);
    }
}
