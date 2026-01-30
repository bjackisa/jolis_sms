<?php
/**
 * Contact Message Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class ContactMessage extends Model
{
    protected static string $table = 'contact_messages';

    public static function getNew(): array
    {
        return self::all("status = 'new'", [], "created_at DESC");
    }

    public static function getAll(): array
    {
        return self::all("1=1", [], "created_at DESC");
    }

    public static function markAsRead(int $id): bool
    {
        $db = self::getDb();
        return $db->update(self::$table, ['status' => 'read'], "id = ?", [$id]);
    }

    public static function markAsReplied(int $id): bool
    {
        $db = self::getDb();
        return $db->update(self::$table, ['status' => 'replied'], "id = ?", [$id]);
    }

    public static function deleteMessage(int $id): bool
    {
        $db = self::getDb();
        return $db->delete(self::$table, "id = ?", [$id]);
    }

    public static function countNew(): int
    {
        return self::count("status = 'new'");
    }
}
