<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Score model 
 * 
 * @property int $id
 * @property string $sbd Registration number
 * @property float|null $toan 
 * @property float|null $ngu_van 
 * @property float|null $ngoai_ngu 
 * @property float|null $vat_li 
 * @property float|null $hoa_hoc 
 * @property float|null $sinh_hoc 
 * @property float|null $lich_su 
 * @property float|null $dia_li 
 * @property float|null $gdcd 
 * @property string|null $ma_ngoai_ngu 
 */
class Score extends Model
{
    use HasFactory;

    protected $table = 'scores';

    protected $fillable = [
        'sbd',
        'toan',
        'ngu_van',
        'ngoai_ngu',
        'vat_li',
        'hoa_hoc',
        'sinh_hoc',
        'lich_su',
        'dia_li',
        'gdcd',
        'ma_ngoai_ngu',
    ];

    protected $casts = [
        'toan' => 'float',
        'ngu_van' => 'float',
        'ngoai_ngu' => 'float',
        'vat_li' => 'float',
        'hoa_hoc' => 'float',
        'sinh_hoc' => 'float',
        'lich_su' => 'float',
        'dia_li' => 'float',
        'gdcd' => 'float',
    ];

    /**
     * Subject configuration with Vietnamese
     */
    public static array $subjects = [
        'toan' => 'Toán',
        'ngu_van' => 'Ngữ Văn',
        'ngoai_ngu' => 'Ngoại Ngữ',
        'vat_li' => 'Vật Lí',
        'hoa_hoc' => 'Hóa Học',
        'sinh_hoc' => 'Sinh Học',
        'lich_su' => 'Lịch Sử',
        'dia_li' => 'Địa Lí',
        'gdcd' => 'GDCD',
    ];

    /**
     * Get all subject columns
     */
    public static function getSubjectColumns(): array
    {
        return array_keys(self::$subjects);
    }

    /**
     * Get subject display name
     */
    public static function getSubjectName(string $column): string
    {
        return self::$subjects[$column] ?? $column;
    }

    /**
     * Calculate total score 
     */
    public function getGroupATotalAttribute(): ?float
    {
        if ($this->toan === null || $this->vat_li === null || $this->hoa_hoc === null) {
            return null;
        }
        return $this->toan + $this->vat_li + $this->hoa_hoc;
    }

    /**
     * Search student
     */
    public static function findByRegistrationNumber(string $sbd): ?self
    {
        return self::where('sbd', $sbd)->first();
    }

    /**
     * Get top students
     */
    public static function getTopGroupA(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereNotNull('toan')
            ->whereNotNull('vat_li')
            ->whereNotNull('hoa_hoc')
            ->selectRaw('*, (toan + vat_li + hoa_hoc) as group_a_total')
            ->orderByDesc('group_a_total')
            ->limit($limit)
            ->get();
    }

    /**
     * Get score statistics by level for a subject using SQL aggregation
     */
    public static function getStatisticsBySubject(string $subject): array
    {
        $result = self::whereNotNull($subject)
            ->selectRaw("
                SUM(CASE WHEN {$subject} >= 8 THEN 1 ELSE 0 END) as excellent,
                SUM(CASE WHEN {$subject} >= 6 AND {$subject} < 8 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN {$subject} >= 4 AND {$subject} < 6 THEN 1 ELSE 0 END) as average,
                SUM(CASE WHEN {$subject} < 4 THEN 1 ELSE 0 END) as poor
            ")
            ->first();

        return [
            'excellent' => (int) ($result->excellent ?? 0),
            'good' => (int) ($result->good ?? 0),
            'average' => (int) ($result->average ?? 0),
            'poor' => (int) ($result->poor ?? 0),
        ];
    }

    /**
     * Get all statistics for all subjects
     */
    public static function getAllSubjectsStatistics(): array
    {
        $statistics = [];
        
        foreach (self::$subjects as $column => $name) {
            $statistics[$column] = [
                'name' => $name,
                'stats' => self::getStatisticsBySubject($column),
            ];
        }

        return $statistics;
    }
}
