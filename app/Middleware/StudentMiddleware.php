<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Auth;

class StudentMiddleware
{
    public function handle(Request $request): bool
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Unauthorized', 'code' => 401]);
                exit;
            }
            
            header('Location: /login');
            exit;
        }

        if (!Auth::isStudent()) {
            if ($request->expectsJson()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Forbidden', 'code' => 403]);
                exit;
            }
            
            header('Location: /instructor/dashboard');
            exit;
        }

        return true;
    }
}
