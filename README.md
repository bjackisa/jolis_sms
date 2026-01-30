# Jolis SMS - School Management System

A comprehensive School Management System built for **Jolis ICT Academy** using OOP PHP without any framework.

## Developer

- **Developer:** Jackisa Daniel Barack
- **Email:** barackdanieljackisa@gmail.com
- **Website:** [jackisa.com](https://jackisa.com)
- **Created:** January 30, 2026

## Features

### Landing Website
- Modern, responsive landing page
- About and Contact pages
- Dynamic statistics display

### Authentication
- Single login page for all roles
- Role-based redirection (Instructor/Student)
- Password reset via email
- Remember me functionality
- Secure password hashing (bcrypt cost 12)

### Instructor Dashboard
- Class management
- Student management
- Subject management
- Exam creation and management
- Results entry with automatic grading
- Homework assignment and grading
- Exam scripts upload
- Reports and analytics
- Notice posting
- Profile management

### Student Dashboard
- View results with term summaries
- Submit homework
- Download exam scripts
- View notices
- Profile management

### Results Management
- **O'Level Grading (UCE):** D1 to F9 with aggregates
- **A'Level Grading (UACE):** A to F with points
- Exam types: BOT (20%), MID (20%), EOT (60%)
- Automatic grade calculation
- Performance charts and analytics

### Academic Structure
- Classes: S1 to S6
- Streams: A, B, C, D
- Levels: O'Level (S1-S4), A'Level (S5-S6)
- Terms: Term 1, Term 2, Term 3
- Subjects with multiple papers

## Technology Stack

- **Backend:** PHP 8.0+ (OOP, no framework)
- **Database:** MySQL with PDO
- **Frontend:** Bootstrap 5, jQuery, Chart.js
- **Email:** PHPMailer
- **Icons:** Bootstrap Icons
- **Tables:** DataTables
- **Notifications:** Toastr

## Installation

1. Clone or download the project to your web server directory

2. Create a MySQL database named `jolis_sms`

3. Import the database schema:
   ```bash
   mysql -u root -p jolis_sms < database/schema.sql
   ```

4. Copy `.env.example` to `.env` and configure:
   ```bash
   cp .env.example .env
   ```

5. Update `.env` with your database and SMTP credentials

6. Install Composer dependencies:
   ```bash
   composer install
   ```

7. Run the database seeder:
   ```bash
   php database/seeders/DatabaseSeeder.php
   ```

8. Configure your web server to point to the `public` directory

## Default Login Credentials

### Instructor
- **Email:** instructor@jolis.academy
- **Password:** password123

### Student
- **Email:** student@jolis.academy
- **Password:** password123

## Directory Structure

```
jolis_sms/
├── app/
│   ├── Controllers/
│   │   ├── Api/
│   │   ├── Instructor/
│   │   └── Student/
│   ├── Core/
│   ├── Middleware/
│   ├── Models/
│   └── Views/
│       ├── auth/
│       ├── errors/
│       ├── home/
│       ├── instructor/
│       ├── layouts/
│       └── student/
├── config/
├── database/
│   └── seeders/
├── public/
│   └── assets/
│       ├── css/
│       └── js/
├── routes/
├── vendor/
├── .env
├── .htaccess
└── composer.json
```

## Uganda Grading System

### O'Level (UCE) - Aggregates
| Grade | Marks | Points | Comment |
|-------|-------|--------|---------|
| D1 | 80-100 | 1 | Distinction |
| D2 | 70-79 | 2 | Very Good |
| C3 | 65-69 | 3 | Good |
| C4 | 60-64 | 4 | Good |
| C5 | 55-59 | 5 | Credit |
| C6 | 50-54 | 6 | Credit |
| P7 | 45-49 | 7 | Pass |
| P8 | 40-44 | 8 | Pass |
| F9 | 0-39 | 9 | Fail |

### A'Level (UACE) - Points
| Grade | Marks | Points | Comment |
|-------|-------|--------|---------|
| A | 80-100 | 6 | Excellent |
| B | 70-79 | 5 | Very Good |
| C | 60-69 | 4 | Good |
| D | 50-59 | 3 | Credit |
| E | 40-49 | 2 | Pass |
| O | 35-39 | 1 | Subsidiary Pass |
| F | 0-34 | 0 | Fail |

## API Endpoints

### Public
- `GET /api/stats` - School statistics

### Authenticated
- `GET /api/dashboard/stats` - Dashboard statistics
- `GET /api/dashboard/charts` - Chart data
- `GET /api/classes` - List classes
- `GET /api/students` - List students
- `GET /api/subjects` - List subjects
- `GET /api/exams` - List exams
- `POST /api/results` - Save result
- `POST /api/results/bulk` - Bulk save results
- `GET /api/grading/calculate` - Calculate grade
- `GET /api/grading/scales/{levelId}` - Get grading scales

## License

All rights reserved. This software is proprietary and confidential.

---

© 2026 Jolis ICT Academy. Developed by [Jackisa Daniel Barack](https://jackisa.com)
