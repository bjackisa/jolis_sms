<?php
/**
 * API Stats Controller (Public)
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;

class StatsController extends Controller
{
    public function index(Request $request): void
    {
        $stats = [
            'classes' => ClassModel::count("status = 'active'"),
            'students' => Student::count(),
            'instructors' => Instructor::count(),
            'subjects' => Subject::count("status = 'active'")
        ];

        $this->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
