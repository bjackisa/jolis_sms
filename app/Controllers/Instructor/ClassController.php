<?php
/**
 * Instructor Class Controller
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
use App\Models\ClassStream;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Subject;

class ClassController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $assignments = [];
        $allClasses = [];
        $allSubjects = [];
        
        if ($instructor && $academicYear) {
            $assignments = $this->getInstructorAssignments($instructor['id'], $academicYear['id']);
            $allClasses = ClassStream::allWithDetails($academicYear['id']);
            $allSubjects = Subject::allWithLevel();
        }

        $this->view('instructor.classes.index', [
            'title' => 'My Classes - Instructor',
            'user' => $user,
            'assignments' => $assignments,
            'allClasses' => $allClasses,
            'allSubjects' => $allSubjects,
            'academicYear' => $academicYear
        ]);
    }

    public function show(Request $request): void
    {
        $classStreamId = (int)$request->param('id');
        $classStream = ClassStream::withDetails($classStreamId);

        if (!$classStream) {
            $this->redirect('/instructor/classes');
            return;
        }

        $students = Student::getByClassStream($classStreamId);

        $this->view('instructor.classes.show', [
            'title' => $classStream['class_code'] . ' ' . $classStream['stream_name'] . ' - Instructor',
            'user' => Auth::user(),
            'classStream' => $classStream,
            'students' => $students
        ]);
    }

    public function assign(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        if (!$instructor || !$academicYear) {
            $this->flash('error', 'Unable to assign class. Please try again.');
            $this->redirect('/instructor/classes');
            return;
        }

        $classStreamId = (int)$request->input('class_stream_id');
        $subjectId = (int)$request->input('subject_id');

        if (!$classStreamId || !$subjectId) {
            $this->flash('error', 'Please select both class and subject.');
            $this->redirect('/instructor/classes');
            return;
        }

        $db = Database::getInstance();
        
        $existing = $db->fetch(
            "SELECT id FROM instructor_subjects WHERE instructor_id = ? AND subject_id = ? AND class_stream_id = ? AND academic_year_id = ?",
            [$instructor['id'], $subjectId, $classStreamId, $academicYear['id']]
        );

        if ($existing) {
            $this->flash('error', 'You are already assigned to this class and subject.');
            $this->redirect('/instructor/classes');
            return;
        }

        $db->insert('instructor_subjects', [
            'instructor_id' => $instructor['id'],
            'subject_id' => $subjectId,
            'class_stream_id' => $classStreamId,
            'academic_year_id' => $academicYear['id']
        ]);

        $this->flash('success', 'Class assigned successfully!');
        $this->redirect('/instructor/classes');
    }

    public function remove(Request $request): void
    {
        $assignmentId = (int)$request->param('id');
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);

        if (!$instructor) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
            return;
        }

        $db = Database::getInstance();
        
        $assignment = $db->fetch(
            "SELECT id FROM instructor_subjects WHERE id = ? AND instructor_id = ?",
            [$assignmentId, $instructor['id']]
        );

        if (!$assignment) {
            $this->json(['success' => false, 'message' => 'Assignment not found'], 404);
            return;
        }

        $db->delete('instructor_subjects', 'id = ?', [$assignmentId]);

        $this->json(['success' => true, 'message' => 'Assignment removed successfully']);
    }

    private function getInstructorAssignments(int $instructorId, int $academicYearId): array
    {
        $db = Database::getInstance();
        $sql = "SELECT ins.id, ins.subject_id, ins.class_stream_id,
                       c.name as class_name, c.code as class_code, 
                       st.name as stream_name, s.name as subject_name,
                       (SELECT COUNT(*) FROM student_enrollments se 
                        WHERE se.class_stream_id = ins.class_stream_id AND se.status = 'active') as student_count
                FROM instructor_subjects ins
                JOIN class_streams cs ON ins.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                JOIN subjects s ON ins.subject_id = s.id
                WHERE ins.instructor_id = ? AND ins.academic_year_id = ?
                ORDER BY c.order_index, st.name, s.name";
        return $db->fetchAll($sql, [$instructorId, $academicYearId]);
    }
}
