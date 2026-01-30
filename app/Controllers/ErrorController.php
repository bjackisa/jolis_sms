<?php
/**
 * Error Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class ErrorController extends Controller
{
    public function notFound(Request $request): void
    {
        $this->view('errors.404', [
            'title' => '404 - Page Not Found'
        ]);
    }

    public function serverError(Request $request): void
    {
        $this->view('errors.500', [
            'title' => '500 - Server Error'
        ]);
    }

    public function forbidden(Request $request): void
    {
        $this->view('errors.403', [
            'title' => '403 - Forbidden'
        ]);
    }
}
