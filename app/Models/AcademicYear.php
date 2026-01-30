<?php
/**
 * AcademicYear Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class AcademicYear extends Model
{
    protected static string $table = 'academic_years';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['name', 'start_date', 'end_date', 'is_current', 'status'];

    public static function current(): ?array
    {
        return self::findBy('is_current', 1);
    }

    public static function active(): array
    {
        return self::where('status', 'active');
    }

    public static function setCurrent(int $id): void
    {
        self::db()->query("UPDATE academic_years SET is_current = 0");
        self::update($id, ['is_current' => 1]);
    }
}
