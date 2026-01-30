<?php
/**
 * Notice Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class Notice extends Model
{
    protected static string $table = 'notices';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'title', 'content', 'target_role', 'target_class_id', 'priority',
        'is_pinned', 'published_at', 'expires_at', 'created_by', 'status'
    ];

    public static function published(): array
    {
        $sql = "SELECT n.*, u.first_name, u.last_name, c.name as class_name
                FROM notices n
                LEFT JOIN users u ON n.created_by = u.id
                LEFT JOIN classes c ON n.target_class_id = c.id
                WHERE n.status = 'published' 
                AND (n.expires_at IS NULL OR n.expires_at > NOW())
                ORDER BY n.is_pinned DESC, n.published_at DESC";
        return self::raw($sql);
    }

    public static function forRole(string $role): array
    {
        $sql = "SELECT n.*, u.first_name, u.last_name
                FROM notices n
                LEFT JOIN users u ON n.created_by = u.id
                WHERE n.status = 'published' 
                AND (n.target_role = 'all' OR n.target_role = ?)
                AND (n.expires_at IS NULL OR n.expires_at > NOW())
                ORDER BY n.is_pinned DESC, n.published_at DESC";
        return self::raw($sql, [$role]);
    }

    public static function forStudent(int $studentId): array
    {
        $enrollment = Student::getCurrentEnrollment($studentId);
        
        $sql = "SELECT n.*, u.first_name, u.last_name
                FROM notices n
                LEFT JOIN users u ON n.created_by = u.id
                WHERE n.status = 'published' 
                AND (n.target_role = 'all' OR n.target_role = 'student')
                AND (n.target_class_id IS NULL" . ($enrollment ? " OR n.target_class_id = ?" : "") . ")
                AND (n.expires_at IS NULL OR n.expires_at > NOW())
                ORDER BY n.is_pinned DESC, n.published_at DESC";
        
        $params = $enrollment ? [$enrollment['class_id'] ?? 0] : [];
        return self::raw($sql, $params);
    }

    public static function recent(int $limit = 5): array
    {
        $sql = "SELECT n.*, u.first_name, u.last_name
                FROM notices n
                LEFT JOIN users u ON n.created_by = u.id
                WHERE n.status = 'published'
                ORDER BY n.published_at DESC
                LIMIT ?";
        return self::raw($sql, [$limit]);
    }
}
