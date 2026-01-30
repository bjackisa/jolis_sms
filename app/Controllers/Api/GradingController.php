<?php
/**
 * API Grading Controller
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
use App\Models\GradingScale;

class GradingController extends Controller
{
    public function calculate(Request $request): void
    {
        $marks = (float)$request->query('marks');
        $levelCode = $request->query('level', 'O');

        if ($levelCode === 'O') {
            $gradeInfo = GradingScale::calculateOLevelGrade($marks);
        } else {
            $gradeInfo = GradingScale::calculateALevelGrade($marks);
        }

        $this->json([
            'success' => true,
            'data' => [
                'marks' => $marks,
                'grade' => $gradeInfo['grade'],
                'points' => $gradeInfo['points'],
                'comment' => $gradeInfo['comment']
            ]
        ]);
    }

    public function scales(Request $request): void
    {
        $levelId = (int)$request->param('levelId');
        $scales = GradingScale::byLevel($levelId);

        $this->json([
            'success' => true,
            'data' => $scales
        ]);
    }
}
