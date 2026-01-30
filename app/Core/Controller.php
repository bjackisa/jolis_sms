<?php
/**
 * Base Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        echo View::render($view, $data);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function session(string $key = null, $value = null)
    {
        if ($key === null) {
            return $_SESSION;
        }

        if ($value !== null) {
            $_SESSION[$key] = $value;
            return $value;
        }

        return $_SESSION[$key] ?? null;
    }

    protected function flash(string $key, $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return;
        }

        $flash = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $flash;
    }

    protected function auth()
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function hasRole(string $role): bool
    {
        $user = $this->auth();
        return $user && ($user['role'] ?? '') === $role;
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $params = [];
                if (strpos($rule, ':') !== false) {
                    list($rule, $paramString) = explode(':', $rule);
                    $params = explode(',', $paramString);
                }

                $error = $this->validateRule($field, $value, $rule, $params, $data);
                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }

        return $errors;
    }

    protected function verifyRecaptcha(Request $request): bool
    {
        if (!defined('RECAPTCHA_SECRET_KEY') || RECAPTCHA_SECRET_KEY === '') {
            return true;
        }

        $token = (string)$request->input('g-recaptcha-response');
        if (trim($token) === '') {
            $_SESSION['_errors'] = array_merge($_SESSION['_errors'] ?? [], [
                'recaptcha' => ['Please confirm you are not a robot.']
            ]);
            return false;
        }

        $payload = http_build_query([
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);

        $resultJson = null;

        if (function_exists('curl_init')) {
            $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $resultJson = curl_exec($ch);
            curl_close($ch);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 10
                ]
            ]);
            $resultJson = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        }

        $result = json_decode((string)$resultJson, true);
        $success = (bool)($result['success'] ?? false);

        if (!$success) {
            $_SESSION['_errors'] = array_merge($_SESSION['_errors'] ?? [], [
                'recaptcha' => ['reCAPTCHA verification failed. Please try again.']
            ]);
            return false;
        }

        return true;
    }

    private function validateRule(string $field, $value, string $rule, array $params, array $data): ?string
    {
        $fieldName = ucfirst(str_replace('_', ' ', $field));

        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    return "{$fieldName} is required.";
                }
                break;

            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "{$fieldName} must be a valid email address.";
                }
                break;

            case 'min':
                if (strlen($value) < (int)$params[0]) {
                    return "{$fieldName} must be at least {$params[0]} characters.";
                }
                break;

            case 'max':
                if (strlen($value) > (int)$params[0]) {
                    return "{$fieldName} must not exceed {$params[0]} characters.";
                }
                break;

            case 'numeric':
                if ($value && !is_numeric($value)) {
                    return "{$fieldName} must be a number.";
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($data[$confirmField] ?? null)) {
                    return "{$fieldName} confirmation does not match.";
                }
                break;

            case 'unique':
                $table = $params[0];
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;
                
                $db = Database::getInstance();
                $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
                $queryParams = [$value];
                
                if ($exceptId) {
                    $sql .= " AND id != ?";
                    $queryParams[] = $exceptId;
                }
                
                $result = $db->fetch($sql, $queryParams);
                if ($result['count'] > 0) {
                    return "{$fieldName} already exists.";
                }
                break;
        }

        return null;
    }

    protected function uploadFile(array $file, string $directory, array $allowedTypes = []): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = PUBLIC_PATH . '/uploads/' . trim($directory, '/') . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
            return null;
        }

        $filename = uniqid() . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/uploads/' . trim($directory, '/') . '/' . $filename;
        }

        return null;
    }
}
