<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Auth;

class GuestMiddleware
{
    public function handle(Request $request): bool
    {
        if (Auth::check()) {
            $role = Auth::role();
            
            if ($role === 'instructor') {
                header('Location: /instructor/dashboard');
            } else {
                header('Location: /student/dashboard');
            }
            exit;
        }

        return true;
    }
}
