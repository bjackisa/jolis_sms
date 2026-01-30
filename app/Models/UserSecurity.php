<?php
/**
 * User Security Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class UserSecurity extends Model
{
    protected static string $table = 'user_security';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['user_id', 'secret_question', 'secret_answer_hash'];

    public static function findByUserId(int $userId): ?array
    {
        return static::rawOne('SELECT * FROM ' . static::$table . ' WHERE user_id = ? LIMIT 1', [$userId]);
    }

    public static function upsertForUser(int $userId, string $question, string $answerHash): void
    {
        $existing = static::findByUserId($userId);

        if ($existing) {
            static::db()->query(
                'UPDATE ' . static::$table . ' SET secret_question = ?, secret_answer_hash = ? WHERE user_id = ?',
                [$question, $answerHash, $userId]
            );
            return;
        }

        static::create([
            'user_id' => $userId,
            'secret_question' => $question,
            'secret_answer_hash' => $answerHash
        ]);
    }
}
