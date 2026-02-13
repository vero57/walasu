<?php

namespace Database\Seeders;

use App\Models\DetailPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Walas;
use Illuminate\Database\Seeder;

/**
 * KehadiranSeeder
 * Generate sample kehadiran (attendance) records for testing synchronization
 */
class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table already has data
        if (Presensi::count() > 0) {
            $this->command->info('Presensi table already has data. Skipping KehadiranSeeder.');
            return;
        }

        $this->command->info('Seeding Kehadiran data...');

        // Get walas and siswa
        $walas = Walas::all();
        $students = Siswa::all();

        if ($walas->isEmpty() || $students->isEmpty()) {
            $this->command->warn('No Walas or Siswa found. Please seed those tables first.');
            return;
        }

        // Define attendance records for February 2026
        $attendanceData = [
            ['date' => '2026-02-03', 'kelas' => 'X SIJA 1', 'walas_idx' => 0],
            ['date' => '2026-02-04', 'kelas' => 'X SIJA 1', 'walas_idx' => 0],
            ['date' => '2026-02-05', 'kelas' => 'X SIJA 1', 'walas_idx' => 0],
            ['date' => '2026-02-06', 'kelas' => 'X SIJA 2', 'walas_idx' => 1],
            ['date' => '2026-02-07', 'kelas' => 'X SIJA 2', 'walas_idx' => 1],
            ['date' => '2026-02-08', 'kelas' => 'X TKJ 1', 'walas_idx' => 2],
            ['date' => '2026-02-09', 'kelas' => 'X TKJ 1', 'walas_idx' => 2],
            ['date' => '2026-02-10', 'kelas' => 'X TKJ 2', 'walas_idx' => 0],
            ['date' => '2026-02-11', 'kelas' => 'X TKJ 2', 'walas_idx' => 0],
            ['date' => '2026-02-12', 'kelas' => 'X SIJA 1', 'walas_idx' => 1],
        ];

        $statuses = ['hadir', 'sakit', 'izin', 'alfa'];

        foreach ($attendanceData as $attendance) {
            $walasIdx = min($attendance['walas_idx'], $walas->count() - 1);
            $walasRecord = $walas->get($walasIdx);

            if (!$walasRecord) continue;

            // Create presensi record
            $presensi = Presensi::create([
                'tanggal' => $attendance['date'],
                'kelas' => $attendance['kelas'],
                'walas_id' => $walasRecord->id,
                'keterangan' => 'Kehadiran ' . date('d-m-Y', strtotime($attendance['date'])),
            ]);

            // Create detail presensi for each student
            foreach ($students as $index => $student) {
                $status = $statuses[$index % count($statuses)];
                
                DetailPresensi::create([
                    'presensis_id' => $presensi->id,
                    'siswas_id' => $student->id,
                    'status' => $status,
                    'keterangan' => "Status: {$status}",
                ]);
            }
        }

        $this->command->info('✅ KehadiranSeeder executed successfully!');
    }
}
