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
        return self::raw(
            "SELECT * FROM " . static::$table . " WHERE status = ? ORDER BY " . static::$primaryKey . " DESC",
            ['new']
        );
    }

    public static function getAll(): array
    {
        return self::all();
    }

    public static function markAsRead(int $id): bool
    {
        return static::update($id, ['status' => 'read']) > 0;
    }

    public static function markAsReplied(int $id): bool
    {
        return static::update($id, ['status' => 'replied']) > 0;
    }

    public static function deleteMessage(int $id): bool
    {
        return static::delete($id) > 0;
    }

    public static function countNew(): int
    {
        return static::count('status = ?', ['new']);
    }
}
