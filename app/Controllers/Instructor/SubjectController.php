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
use App\Core\Database;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\ClassStream;
use App\Models\AcademicYear;

class SubjectController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $assignments = [];
        $subjectClasses = [];
        $allSubjects = [];
        $allClasses = [];
        
        if ($instructor && $academicYear) {
            $assignments = $this->getSubjectAssignments($instructor['id'], $academicYear['id']);
            $subjectClasses = $this->getSubjectClassesGrouped($instructor['id'], $academicYear['id']);
            $allSubjects = Subject::allWithLevel();
            $allClasses = ClassStream::allWithDetails($academicYear['id']);
        }

        $this->view('instructor.subjects.index', [
            'title' => 'My Subjects - Instructor',
            'user' => $user,
            'assignments' => $assignments,
            'subjectClasses' => $subjectClasses,
            'allSubjects' => $allSubjects,
            'allClasses' => $allClasses,
            'academicYear' => $academicYear
        ]);
    }

    public function assign(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        if (!$instructor || !$academicYear) {
            $this->flash('error', 'Unable to assign subject. Please try again.');
            $this->redirect('/instructor/subjects');
            return;
        }

        $subjectId = (int)$request->input('subject_id');
        $classStreamId = (int)$request->input('class_stream_id');

        if (!$subjectId || !$classStreamId) {
            $this->flash('error', 'Please select both subject and class.');
            $this->redirect('/instructor/subjects');
            return;
        }

        $db = Database::getInstance();
        
        $existing = $db->fetch(
            "SELECT id FROM instructor_subjects WHERE instructor_id = ? AND subject_id = ? AND class_stream_id = ? AND academic_year_id = ?",
            [$instructor['id'], $subjectId, $classStreamId, $academicYear['id']]
        );

        if ($existing) {
            $this->flash('error', 'You are already assigned to this subject and class.');
            $this->redirect('/instructor/subjects');
            return;
        }

        $db->insert('instructor_subjects', [
            'instructor_id' => $instructor['id'],
            'subject_id' => $subjectId,
            'class_stream_id' => $classStreamId,
            'academic_year_id' => $academicYear['id']
        ]);

        $this->flash('success', 'Subject assigned successfully!');
        $this->redirect('/instructor/subjects');
    }

    private function getSubjectAssignments(int $instructorId, int $academicYearId): array
    {
        $db = Database::getInstance();
        $sql = "SELECT s.id as subject_id, s.name as subject_name, s.code as subject_code,
                       l.code as level_code, sc.name as category_name, s.paper_count,
                       COUNT(DISTINCT ins.class_stream_id) as class_count
                FROM instructor_subjects ins
                JOIN subjects s ON ins.subject_id = s.id
                JOIN levels l ON s.level_id = l.id
                LEFT JOIN subject_categories sc ON s.category_id = sc.id
                WHERE ins.instructor_id = ? AND ins.academic_year_id = ?
                GROUP BY s.id, s.name, s.code, l.code, sc.name, s.paper_count
                ORDER BY l.code, s.name";
        return $db->fetchAll($sql, [$instructorId, $academicYearId]);
    }

    private function getSubjectClassesGrouped(int $instructorId, int $academicYearId): array
    {
        $db = Database::getInstance();
        $sql = "SELECT s.id, s.name, ins.class_stream_id, c.code as class_code, st.name as stream_name
                FROM instructor_subjects ins
                JOIN subjects s ON ins.subject_id = s.id
                JOIN class_streams cs ON ins.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                WHERE ins.instructor_id = ? AND ins.academic_year_id = ?
                ORDER BY s.name, c.order_index, st.name";
        $rows = $db->fetchAll($sql, [$instructorId, $academicYearId]);
        
        $grouped = [];
        foreach ($rows as $row) {
            if (!isset($grouped[$row['id']])) {
                $grouped[$row['id']] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'classes' => []
                ];
            }
            $grouped[$row['id']]['classes'][] = [
                'class_stream_id' => $row['class_stream_id'],
                'class_code' => $row['class_code'],
                'stream_name' => $row['stream_name']
            ];
        }
        
        return array_values($grouped);
    }
}
