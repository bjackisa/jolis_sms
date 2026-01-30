<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE email = ? AND status = 'active'";
        $user = $db->fetch($sql, [$email]);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::login($user);
        return true;
    }

    public static function login(array $user): void
    {
        unset($user['password']);
        $_SESSION['user'] = $user;
        $_SESSION['logged_in_at'] = time();
        
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['logged_in_at']);
        session_destroy();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function isRole(string $role): bool
    {
        return self::role() === $role;
    }

    public static function isInstructor(): bool
    {
        return self::isRole('instructor');
    }

    public static function isStudent(): bool
    {
        return self::isRole('student');
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_COST]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function generateToken(int $length = 64): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function refresh(): void
    {
        if (self::check()) {
            $db = Database::getInstance();
            $user = $db->fetch("SELECT * FROM users WHERE id = ?", [self::id()]);
            if ($user) {
                self::login($user);
            }
        }
    }
}
