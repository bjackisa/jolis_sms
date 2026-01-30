<?php
/**
 * Term Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Term extends Model
{
    protected static string $table = 'terms';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'academic_year_id', 'name', 'term_number', 'start_date', 'end_date', 'is_current', 'status'
    ];

    public static function current(): ?array
    {
        $sql = "SELECT t.*, ay.name as academic_year_name
                FROM terms t
                JOIN academic_years ay ON t.academic_year_id = ay.id
                WHERE t.is_current = 1";
        return self::rawOne($sql);
    }

    public static function byAcademicYear(int $academicYearId): array
    {
        $sql = "SELECT * FROM terms WHERE academic_year_id = ? ORDER BY term_number";
        return self::raw($sql, [$academicYearId]);
    }

    public static function setCurrent(int $id): void
    {
        self::db()->query("UPDATE terms SET is_current = 0");
        self::update($id, ['is_current' => 1]);
    }
}
