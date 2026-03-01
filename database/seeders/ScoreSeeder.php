<?php

namespace Database\Seeders;

use App\Models\Score;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    /**
     * Database seeding with CSV file.
     */
    public function run(): void
    {
        $csvPath = base_path('./database/diem_thi_thpt_2024.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $this->command->info('Starting to import scores from CSV...');
        
        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command->error('Could not open CSV file');
            return;
        }

        // Read header row
        $header = fgetcsv($handle);
        if ($header === false) {
            $this->command->error('Could not read CSV header');
            fclose($handle);
            return;
        }

        // Truncate existing data
        DB::table('scores')->truncate();

        $batchSize = 1000;
        $batch = [];
        $totalImported = 0;

        $this->command->info('Processing CSV data...');

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            
            $batch[] = [
                'sbd' => $data['sbd'],
                'toan' => $this->parseScore($data['toan'] ?? null),
                'ngu_van' => $this->parseScore($data['ngu_van'] ?? null),
                'ngoai_ngu' => $this->parseScore($data['ngoai_ngu'] ?? null),
                'vat_li' => $this->parseScore($data['vat_li'] ?? null),
                'hoa_hoc' => $this->parseScore($data['hoa_hoc'] ?? null),
                'sinh_hoc' => $this->parseScore($data['sinh_hoc'] ?? null),
                'lich_su' => $this->parseScore($data['lich_su'] ?? null),
                'dia_li' => $this->parseScore($data['dia_li'] ?? null),
                'gdcd' => $this->parseScore($data['gdcd'] ?? null),
                'ma_ngoai_ngu' => !empty($data['ma_ngoai_ngu']) ? $data['ma_ngoai_ngu'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('scores')->insert($batch);
                $totalImported += count($batch);
                $this->command->info("Imported {$totalImported} records...");
                $batch = [];
            }
        }

        // Insert remaining records
        if (!empty($batch)) {
            DB::table('scores')->insert($batch);
            $totalImported += count($batch);
        }

        fclose($handle);

        $this->command->info("Successfully imported {$totalImported} records!");
    }

    /**
     * Parse score value
     */
    private function parseScore(?string $value): ?float
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        return (float) $value;
    }
}
