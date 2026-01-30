<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Auth;

class AuthMiddleware
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
            
            $_SESSION['_intended_url'] = $request->getUri();
            header('Location: /login');
            exit;
        }

        return true;
    }
}
