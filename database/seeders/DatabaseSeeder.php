<?php
require_once dirname(__DIR__, 2) . '/config/autoload.php';
require_once dirname(__DIR__, 2) . '/config/config.php';

use App\Core\Database;
use App\Core\Auth;

class DatabaseSeeder
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function run(): void
    {
        echo "Starting database seeding...\n\n";

        $this->seedLevels();
        $this->seedClasses();
        $this->seedStreams();
        $this->seedAcademicYears();
        $this->seedTerms();
        $this->seedClassStreams();
        $this->seedSubjectCategories();
        $this->seedSubjects();
        $this->seedSubjectPapers();
        $this->seedExamTypes();
        $this->seedGradingScales();
        $this->seedUsers();
        $this->seedSettings();

        echo "\nDatabase seeding completed successfully!\n";
    }

    private function seedLevels(): void
    {
        echo "Seeding levels...\n";
        
        $levels = [
            ['name' => "O'Level", 'code' => 'O', 'description' => 'Ordinary Level - Senior 1 to Senior 4'],
            ['name' => "A'Level", 'code' => 'A', 'description' => 'Advanced Level - Senior 5 to Senior 6']
        ];

        foreach ($levels as $level) {
            $this->db->query(
                "INSERT INTO levels (name, code, description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = name",
                [$level['name'], $level['code'], $level['description']]
            );
        }
    }

    private function seedClasses(): void
    {
        echo "Seeding classes...\n";
        
        $classes = [
            ['level_code' => 'O', 'name' => 'Senior 1', 'code' => 'S1', 'order_index' => 1],
            ['level_code' => 'O', 'name' => 'Senior 2', 'code' => 'S2', 'order_index' => 2],
            ['level_code' => 'O', 'name' => 'Senior 3', 'code' => 'S3', 'order_index' => 3],
            ['level_code' => 'O', 'name' => 'Senior 4', 'code' => 'S4', 'order_index' => 4],
            ['level_code' => 'A', 'name' => 'Senior 5', 'code' => 'S5', 'order_index' => 5],
            ['level_code' => 'A', 'name' => 'Senior 6', 'code' => 'S6', 'order_index' => 6]
        ];

        foreach ($classes as $class) {
            $level = $this->db->fetch("SELECT id FROM levels WHERE code = ?", [$class['level_code']]);
            if ($level) {
                $this->db->query(
                    "INSERT INTO classes (level_id, name, code, order_index) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = name",
                    [$level['id'], $class['name'], $class['code'], $class['order_index']]
                );
            }
        }
    }

    private function seedStreams(): void
    {
        echo "Seeding streams...\n";
        
        $streams = ['A', 'B', 'C', 'D'];

        foreach ($streams as $stream) {
            $this->db->query(
                "INSERT INTO streams (name) VALUES (?) ON DUPLICATE KEY UPDATE name = name",
                [$stream]
            );
        }
    }

    private function seedAcademicYears(): void
    {
        echo "Seeding academic years...\n";
        
        $years = [
            ['name' => '2025', 'start_date' => '2025-02-01', 'end_date' => '2025-12-15', 'is_current' => 0],
            ['name' => '2026', 'start_date' => '2026-02-01', 'end_date' => '2026-12-15', 'is_current' => 1]
        ];

        foreach ($years as $year) {
            $this->db->query(
                "INSERT INTO academic_years (name, start_date, end_date, is_current) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE is_current = VALUES(is_current)",
                [$year['name'], $year['start_date'], $year['end_date'], $year['is_current']]
            );
        }
    }

    private function seedTerms(): void
    {
        echo "Seeding terms...\n";
        
        $academicYear = $this->db->fetch("SELECT id FROM academic_years WHERE is_current = 1");
        
        if ($academicYear) {
            $terms = [
                ['name' => 'Term 1', 'term_number' => 1, 'start_date' => '2026-02-01', 'end_date' => '2026-05-01', 'is_current' => 1],
                ['name' => 'Term 2', 'term_number' => 2, 'start_date' => '2026-05-15', 'end_date' => '2026-08-15', 'is_current' => 0],
                ['name' => 'Term 3', 'term_number' => 3, 'start_date' => '2026-09-01', 'end_date' => '2026-12-15', 'is_current' => 0]
            ];

            foreach ($terms as $term) {
                $this->db->query(
                    "INSERT INTO terms (academic_year_id, name, term_number, start_date, end_date, is_current) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE is_current = VALUES(is_current)",
                    [$academicYear['id'], $term['name'], $term['term_number'], $term['start_date'], $term['end_date'], $term['is_current']]
                );
            }
        }
    }

    private function seedClassStreams(): void
    {
        echo "Seeding class streams...\n";
        
        $academicYear = $this->db->fetch("SELECT id FROM academic_years WHERE is_current = 1");
        $classes = $this->db->fetchAll("SELECT id FROM classes");
        $streams = $this->db->fetchAll("SELECT id FROM streams");

        if ($academicYear) {
            foreach ($classes as $class) {
                foreach ($streams as $stream) {
                    $this->db->query(
                        "INSERT INTO class_streams (class_id, stream_id, academic_year_id, capacity) VALUES (?, ?, ?, 50) ON DUPLICATE KEY UPDATE capacity = capacity",
                        [$class['id'], $stream['id'], $academicYear['id']]
                    );
                }
            }
        }
    }

    private function seedSubjectCategories(): void
    {
        echo "Seeding subject categories...\n";
        
        $categories = [
            ['name' => 'Sciences', 'description' => 'Science subjects including Physics, Chemistry, Biology'],
            ['name' => 'Languages', 'description' => 'Language subjects including English, Literature'],
            ['name' => 'Mathematics', 'description' => 'Mathematics and related subjects'],
            ['name' => 'Humanities', 'description' => 'History, Geography, Religious Education'],
            ['name' => 'Technical', 'description' => 'Technical and vocational subjects'],
            ['name' => 'Arts', 'description' => 'Fine Art, Music, and related subjects']
        ];

        foreach ($categories as $cat) {
            $this->db->query(
                "INSERT INTO subject_categories (name, description) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = name",
                [$cat['name'], $cat['description']]
            );
        }
    }

    private function seedSubjects(): void
    {
        echo "Seeding subjects...\n";
        
        $oLevel = $this->db->fetch("SELECT id FROM levels WHERE code = 'O'");
        $aLevel = $this->db->fetch("SELECT id FROM levels WHERE code = 'A'");
        
        $sciences = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Sciences'");
        $languages = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Languages'");
        $maths = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Mathematics'");
        $humanities = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Humanities'");
        $technical = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Technical'");
        $arts = $this->db->fetch("SELECT id FROM subject_categories WHERE name = 'Arts'");

        $oLevelSubjects = [
            ['category_id' => $languages['id'], 'name' => 'English Language', 'code' => 'ENG', 'paper_count' => 2, 'is_compulsory' => 1],
            ['category_id' => $languages['id'], 'name' => 'Literature in English', 'code' => 'LIT', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $maths['id'], 'name' => 'Mathematics', 'code' => 'MTH', 'paper_count' => 2, 'is_compulsory' => 1],
            ['category_id' => $sciences['id'], 'name' => 'Physics', 'code' => 'PHY', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $sciences['id'], 'name' => 'Chemistry', 'code' => 'CHE', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $sciences['id'], 'name' => 'Biology', 'code' => 'BIO', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'History', 'code' => 'HIS', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Geography', 'code' => 'GEO', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Christian Religious Education', 'code' => 'CRE', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Islamic Religious Education', 'code' => 'IRE', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $technical['id'], 'name' => 'Computer Studies', 'code' => 'ICT', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $technical['id'], 'name' => 'Agriculture', 'code' => 'AGR', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $arts['id'], 'name' => 'Fine Art', 'code' => 'ART', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $languages['id'], 'name' => 'French', 'code' => 'FRE', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $languages['id'], 'name' => 'Kiswahili', 'code' => 'KIS', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $technical['id'], 'name' => 'Entrepreneurship Education', 'code' => 'ENT', 'paper_count' => 1, 'is_compulsory' => 0]
        ];

        $aLevelSubjects = [
            ['category_id' => $sciences['id'], 'name' => 'Physics', 'code' => 'PHY', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $sciences['id'], 'name' => 'Chemistry', 'code' => 'CHE', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $sciences['id'], 'name' => 'Biology', 'code' => 'BIO', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $maths['id'], 'name' => 'Mathematics', 'code' => 'MTH', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $maths['id'], 'name' => 'Subsidiary Mathematics', 'code' => 'SMT', 'paper_count' => 1, 'is_compulsory' => 0],
            ['category_id' => $languages['id'], 'name' => 'General Paper', 'code' => 'GP', 'paper_count' => 1, 'is_compulsory' => 1],
            ['category_id' => $languages['id'], 'name' => 'Literature in English', 'code' => 'LIT', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'History', 'code' => 'HIS', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Geography', 'code' => 'GEO', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Economics', 'code' => 'ECO', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $humanities['id'], 'name' => 'Divinity', 'code' => 'DIV', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $technical['id'], 'name' => 'Computer Science', 'code' => 'CSC', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $technical['id'], 'name' => 'Entrepreneurship', 'code' => 'ENT', 'paper_count' => 2, 'is_compulsory' => 0],
            ['category_id' => $arts['id'], 'name' => 'Fine Art', 'code' => 'ART', 'paper_count' => 3, 'is_compulsory' => 0],
            ['category_id' => $languages['id'], 'name' => 'French', 'code' => 'FRE', 'paper_count' => 3, 'is_compulsory' => 0]
        ];

        foreach ($oLevelSubjects as $subject) {
            $this->db->query(
                "INSERT INTO subjects (category_id, level_id, name, code, paper_count, is_compulsory) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = name",
                [$subject['category_id'], $oLevel['id'], $subject['name'], $subject['code'], $subject['paper_count'], $subject['is_compulsory']]
            );
        }

        foreach ($aLevelSubjects as $subject) {
            $this->db->query(
                "INSERT INTO subjects (category_id, level_id, name, code, paper_count, is_compulsory) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = name",
                [$subject['category_id'], $aLevel['id'], $subject['name'], $subject['code'], $subject['paper_count'], $subject['is_compulsory']]
            );
        }
    }

    private function seedSubjectPapers(): void
    {
        echo "Seeding subject papers...\n";
        
        $subjects = $this->db->fetchAll("SELECT id, name, paper_count FROM subjects");

        foreach ($subjects as $subject) {
            for ($i = 1; $i <= $subject['paper_count']; $i++) {
                $weight = round(100 / $subject['paper_count'], 2);
                $this->db->query(
                    "INSERT INTO subject_papers (subject_id, paper_number, name, max_marks, weight_percentage) VALUES (?, ?, ?, 100, ?) ON DUPLICATE KEY UPDATE name = name",
                    [$subject['id'], $i, "Paper {$i}", $weight]
                );
            }
        }
    }

    private function seedExamTypes(): void
    {
        echo "Seeding exam types...\n";
        
        $examTypes = [
            ['name' => 'Beginning of Term', 'code' => 'BOT', 'weight_percentage' => 20.00, 'is_national' => 0, 'description' => 'Beginning of Term Examination'],
            ['name' => 'Mid Term', 'code' => 'MID', 'weight_percentage' => 20.00, 'is_national' => 0, 'description' => 'Mid Term Examination'],
            ['name' => 'End of Term', 'code' => 'EOT', 'weight_percentage' => 60.00, 'is_national' => 0, 'description' => 'End of Term Examination'],
            ['name' => 'Uganda Certificate of Education', 'code' => 'UCE', 'weight_percentage' => 100.00, 'is_national' => 1, 'description' => 'National O-Level Examination set by UNEB'],
            ['name' => 'Uganda Advanced Certificate of Education', 'code' => 'UACE', 'weight_percentage' => 100.00, 'is_national' => 1, 'description' => 'National A-Level Examination set by UNEB']
        ];

        foreach ($examTypes as $type) {
            $this->db->query(
                "INSERT INTO exam_types (name, code, weight_percentage, is_national, description) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = name",
                [$type['name'], $type['code'], $type['weight_percentage'], $type['is_national'], $type['description']]
            );
        }
    }

    private function seedGradingScales(): void
    {
        echo "Seeding grading scales...\n";
        
        $oLevel = $this->db->fetch("SELECT id FROM levels WHERE code = 'O'");
        $aLevel = $this->db->fetch("SELECT id FROM levels WHERE code = 'A'");

        $oLevelGrades = [
            ['grade' => 'D1', 'min_marks' => 80, 'max_marks' => 100, 'points' => 1, 'comment' => 'Distinction'],
            ['grade' => 'D2', 'min_marks' => 70, 'max_marks' => 79, 'points' => 2, 'comment' => 'Very Good'],
            ['grade' => 'C3', 'min_marks' => 65, 'max_marks' => 69, 'points' => 3, 'comment' => 'Good'],
            ['grade' => 'C4', 'min_marks' => 60, 'max_marks' => 64, 'points' => 4, 'comment' => 'Good'],
            ['grade' => 'C5', 'min_marks' => 55, 'max_marks' => 59, 'points' => 5, 'comment' => 'Credit'],
            ['grade' => 'C6', 'min_marks' => 50, 'max_marks' => 54, 'points' => 6, 'comment' => 'Credit'],
            ['grade' => 'P7', 'min_marks' => 45, 'max_marks' => 49, 'points' => 7, 'comment' => 'Pass'],
            ['grade' => 'P8', 'min_marks' => 40, 'max_marks' => 44, 'points' => 8, 'comment' => 'Pass'],
            ['grade' => 'F9', 'min_marks' => 0, 'max_marks' => 39, 'points' => 9, 'comment' => 'Fail']
        ];

        $aLevelGrades = [
            ['grade' => 'A', 'min_marks' => 80, 'max_marks' => 100, 'points' => 6, 'comment' => 'Excellent'],
            ['grade' => 'B', 'min_marks' => 70, 'max_marks' => 79, 'points' => 5, 'comment' => 'Very Good'],
            ['grade' => 'C', 'min_marks' => 60, 'max_marks' => 69, 'points' => 4, 'comment' => 'Good'],
            ['grade' => 'D', 'min_marks' => 50, 'max_marks' => 59, 'points' => 3, 'comment' => 'Credit'],
            ['grade' => 'E', 'min_marks' => 40, 'max_marks' => 49, 'points' => 2, 'comment' => 'Pass'],
            ['grade' => 'O', 'min_marks' => 35, 'max_marks' => 39, 'points' => 1, 'comment' => 'Subsidiary Pass'],
            ['grade' => 'F', 'min_marks' => 0, 'max_marks' => 34, 'points' => 0, 'comment' => 'Fail']
        ];

        foreach ($oLevelGrades as $grade) {
            $this->db->query(
                "INSERT INTO grading_scales (level_id, grade, min_marks, max_marks, points, comment) VALUES (?, ?, ?, ?, ?, ?)",
                [$oLevel['id'], $grade['grade'], $grade['min_marks'], $grade['max_marks'], $grade['points'], $grade['comment']]
            );
        }

        foreach ($aLevelGrades as $grade) {
            $this->db->query(
                "INSERT INTO grading_scales (level_id, grade, min_marks, max_marks, points, comment) VALUES (?, ?, ?, ?, ?, ?)",
                [$aLevel['id'], $grade['grade'], $grade['min_marks'], $grade['max_marks'], $grade['points'], $grade['comment']]
            );
        }
    }

    private function seedUsers(): void
    {
        echo "Seeding users...\n";
        
        $password = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]);

        $instructors = [
            ['email' => 'instructor@jolis.academy', 'first_name' => 'John', 'last_name' => 'Mukasa', 'phone' => '+256700100100'],
            ['email' => 'mary.nambi@jolis.academy', 'first_name' => 'Mary', 'last_name' => 'Nambi', 'phone' => '+256700100101'],
            ['email' => 'peter.ochieng@jolis.academy', 'first_name' => 'Peter', 'last_name' => 'Ochieng', 'phone' => '+256700100102']
        ];

        foreach ($instructors as $instructor) {
            $existing = $this->db->fetch("SELECT id FROM users WHERE email = ?", [$instructor['email']]);
            if (!$existing) {
                $userId = $this->db->insert('users', [
                    'email' => $instructor['email'],
                    'password' => $password,
                    'role' => 'instructor',
                    'first_name' => $instructor['first_name'],
                    'last_name' => $instructor['last_name'],
                    'phone' => $instructor['phone'],
                    'status' => 'active'
                ]);

                $this->db->insert('instructors', [
                    'user_id' => $userId,
                    'employee_id' => 'EMP' . str_pad($userId, 4, '0', STR_PAD_LEFT),
                    'qualification' => 'Bachelor of Education',
                    'date_joined' => '2024-01-15'
                ]);
            }
        }

        $students = [
            ['email' => 'student@jolis.academy', 'first_name' => 'Grace', 'last_name' => 'Achieng', 'gender' => 'female'],
            ['email' => 'david.okello@jolis.academy', 'first_name' => 'David', 'last_name' => 'Okello', 'gender' => 'male'],
            ['email' => 'sarah.nakato@jolis.academy', 'first_name' => 'Sarah', 'last_name' => 'Nakato', 'gender' => 'female'],
            ['email' => 'brian.mugisha@jolis.academy', 'first_name' => 'Brian', 'last_name' => 'Mugisha', 'gender' => 'male'],
            ['email' => 'faith.nalubega@jolis.academy', 'first_name' => 'Faith', 'last_name' => 'Nalubega', 'gender' => 'female']
        ];

        $classStream = $this->db->fetch("SELECT cs.id FROM class_streams cs JOIN classes c ON cs.class_id = c.id WHERE c.code = 'S1' LIMIT 1");
        $academicYear = $this->db->fetch("SELECT id FROM academic_years WHERE is_current = 1");
        $term = $this->db->fetch("SELECT id FROM terms WHERE is_current = 1");

        foreach ($students as $index => $student) {
            $existing = $this->db->fetch("SELECT id FROM users WHERE email = ?", [$student['email']]);
            if (!$existing) {
                $userId = $this->db->insert('users', [
                    'email' => $student['email'],
                    'password' => $password,
                    'role' => 'student',
                    'first_name' => $student['first_name'],
                    'last_name' => $student['last_name'],
                    'status' => 'active'
                ]);

                $studentId = $this->db->insert('students', [
                    'user_id' => $userId,
                    'student_number' => 'STU' . date('Y') . str_pad($userId, 4, '0', STR_PAD_LEFT),
                    'admission_date' => '2026-02-01',
                    'date_of_birth' => '2010-0' . ($index + 1) . '-15',
                    'gender' => $student['gender'],
                    'guardian_name' => 'Parent of ' . $student['first_name'],
                    'guardian_phone' => '+25670010020' . $index
                ]);

                if ($classStream && $academicYear && $term) {
                    $this->db->insert('student_enrollments', [
                        'student_id' => $studentId,
                        'class_stream_id' => $classStream['id'],
                        'academic_year_id' => $academicYear['id'],
                        'term_id' => $term['id'],
                        'enrollment_date' => '2026-02-01',
                        'status' => 'active'
                    ]);
                }
            }
        }
    }

    private function seedSettings(): void
    {
        echo "Seeding settings...\n";
        
        $settings = [
            ['key' => 'school_name', 'value' => 'Jolis ICT Academy', 'type' => 'string'],
            ['key' => 'school_motto', 'value' => 'Excellence Through Technology', 'type' => 'string'],
            ['key' => 'school_email', 'value' => 'info@jolis.academy', 'type' => 'string'],
            ['key' => 'school_phone', 'value' => '+256700000000', 'type' => 'string'],
            ['key' => 'school_address', 'value' => 'Kampala, Uganda', 'type' => 'string'],
            ['key' => 'bot_weight', 'value' => '20', 'type' => 'integer'],
            ['key' => 'mid_weight', 'value' => '20', 'type' => 'integer'],
            ['key' => 'eot_weight', 'value' => '60', 'type' => 'integer']
        ];

        foreach ($settings as $setting) {
            $this->db->query(
                "INSERT INTO settings (`key`, `value`, `type`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)",
                [$setting['key'], $setting['value'], $setting['type']]
            );
        }
    }
}

define('BASE_PATH', dirname(__DIR__, 2));
$seeder = new DatabaseSeeder();
$seeder->run();
