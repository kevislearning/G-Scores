<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class ScoreController extends Controller
{
    /**
     * Index page
     */
    public function index(): View
    {
        return view('scores.index');
    }

    /**
     * Search student's scores
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'sbd' => ['required', 'string', 'regex:/^[0-9]{8}$/'],
            ], [
                'sbd.required' => 'Vui lòng nhập số báo danh',
                'sbd.regex' => 'Số báo danh phải có đúng 8 chữ số',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['sbd'][0] ?? 'Dữ liệu không hợp lệ',
            ], 422);
        }

        $score = Score::findByRegistrationNumber($validated['sbd']);

        if (!$score) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thí sinh với số báo danh này',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatScoreData($score),
        ]);
    }

    /**
     * Statistics for all subjects
     */
    public function statistics(): JsonResponse
    {
        $statistics = Score::getAllSubjectsStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Top 10 students of group A
     */
    public function topGroupA(): JsonResponse
    {
        $topStudents = Score::getTopGroupA(10);

        $formattedStudents = $topStudents->map(function ($student, $index) {
            return [
                'rank' => $index + 1,
                'sbd' => $student->sbd,
                'toan' => $student->toan,
                'vat_li' => $student->vat_li,
                'hoa_hoc' => $student->hoa_hoc,
                'total' => $student->group_a_total,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedStudents,
        ]);
    }

    /**
     * Format score data for API response
     */
    private function formatScoreData(Score $score): array
    {
        $scores = [];
        foreach (Score::$subjects as $column => $name) {
            $value = $score->{$column};
            $scores[] = [
                'subject' => $name,
                'score' => $value,
                'display' => $value !== null ? number_format($value, 2) : '-',
            ];
        }

        return [
            'sbd' => $score->sbd,
            'ma_ngoai_ngu' => $score->ma_ngoai_ngu,
            'scores' => $scores,
        ];
    }
}
