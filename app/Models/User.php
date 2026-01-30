<?php
/**
 * User Model
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'email', 'password', 'role', 'first_name', 'last_name',
        'phone', 'avatar', 'status', 'email_verified_at',
        'remember_token', 'last_login_at'
    ];
    protected static array $hidden = ['password', 'remember_token'];

    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }

    public static function instructors(): array
    {
        return self::where('role', 'instructor');
    }

    public static function students(): array
    {
        return self::where('role', 'student');
    }

    public static function active(): array
    {
        return self::where('status', 'active');
    }

    public static function getFullName(array $user): string
    {
        return $user['first_name'] . ' ' . $user['last_name'];
    }

    public static function updateLastLogin(int $userId): void
    {
        self::update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    public static function withProfile(int $userId): ?array
    {
        $user = self::find($userId);
        if (!$user) return null;

        if ($user['role'] === 'instructor') {
            $profile = Instructor::findBy('user_id', $userId);
        } else {
            $profile = Student::findBy('user_id', $userId);
        }

        $user['profile'] = $profile;
        return $user;
    }
}
