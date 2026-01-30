<?php
/**
 * Instructor Subject Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers\Instructor;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\AcademicYear;

class SubjectController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $subjects = [];
        if ($instructor && $academicYear) {
            $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);
            
            foreach ($subjects as &$subject) {
                $subject['papers'] = Subject::getPapers($subject['id']);
            }
        }

        $this->view('instructor.subjects.index', [
            'title' => 'My Subjects - Instructor',
            'user' => $user,
            'subjects' => $subjects,
            'academicYear' => $academicYear
        ]);
    }
}
