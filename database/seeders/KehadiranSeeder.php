<?php

namespace Database\Seeders;

use App\Models\DetailPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Get walas users for data
        $walas = User::whereHas('roles', function ($query) {
            $query->where('name', 'walas');
        })->limit(3)->get();

        // Get some students
        $students = Siswa::limit(10)->get();

        if ($walas->isEmpty() || $students->isEmpty()) {
            echo "⚠️ Skipping KehadiranSeeder: Not enough walas or students in database\n";
            return;
        }

        // Define date range for February 2026
        $startDate = \Carbon\Carbon::createFromDate(2026, 2, 1);
        $attendanceData = [
            ['date' => '2026-02-03', 'kelas' => 'X SIJA 1', 'walas_id' => $walas[0]->id],
            ['date' => '2026-02-04', 'kelas' => 'X SIJA 1', 'walas_id' => $walas[0]->id],
            ['date' => '2026-02-05', 'kelas' => 'X SIJA 1', 'walas_id' => $walas[0]->id],
            ['date' => '2026-02-06', 'kelas' => 'X SIJA 2', 'walas_id' => $walas[1]->id],
            ['date' => '2026-02-07', 'kelas' => 'X SIJA 2', 'walas_id' => $walas[1]->id],
            ['date' => '2026-02-08', 'kelas' => 'X TKJ 1', 'walas_id' => $walas[2]->id],
            ['date' => '2026-02-09', 'kelas' => 'X TKJ 1', 'walas_id' => $walas[2]->id],
            ['date' => '2026-02-10', 'kelas' => 'X TKJ 2', 'walas_id' => $walas[0]->id],
            ['date' => '2026-02-11', 'kelas' => 'X TKJ 2', 'walas_id' => $walas[0]->id],
            ['date' => '2026-02-12', 'kelas' => 'X SIJA 1', 'walas_id' => $walas[1]->id],
        ];

        $statuses = ['hadir', 'sakit', 'izin', 'alfa'];

        foreach ($attendanceData as $attendance) {
            // Create or get presensi record
            $presensi = Presensi::firstOrCreate(
                [
                    'tanggal' => $attendance['date'],
                    'kelas' => $attendance['kelas'],
                    'walas_id' => $attendance['walas_id'],
                ],
                [
                    'tanggal' => $attendance['date'],
                    'kelas' => $attendance['kelas'],
                    'walas_id' => $attendance['walas_id'],
                    'keterangan' => 'Kehadiran ' . date('d-m-Y', strtotime($attendance['date'])),
                ]
            );

            // Create detail presensi for each student
            foreach ($students as $index => $student) {
                $status = $statuses[$index % count($statuses)];
                
                DetailPresensi::firstOrCreate(
                    [
                        'presensis_id' => $presensi->id,
                        'siswas_id' => $student->id,
                    ],
                    [
                        'presensis_id' => $presensi->id,
                        'siswas_id' => $student->id,
                        'status' => $status,
                        'keterangan' => "Status: {$status}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        echo "✅ KehadiranSeeder executed successfully!\n";
    }
}
