<?php

namespace App\Http\Controllers\Api;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * SECTION B: Attendance Data API Endpoints
 * API untuk expose data Presensi dari Walas ke Kesiswaan
 */
class WalasAttendanceController extends WalasApiController
{
    /**
     * GET /api/v1/walas/attendance
     * Get attendance records with filters
     * Query: ?student_id=X&class_id=Y&start_date=2025-01-01&end_date=2025-01-31&page=1&limit=20
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'nullable|integer',
                'class_id' => 'nullable|integer',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 20;

            $query = Presensi::query();

            // Filter by student
            if (!empty($validated['student_id'])) {
                $query->where('id_siswa', $validated['student_id']);
            }

            // Filter by class
            if (!empty($validated['class_id'])) {
                $query->where('id_rombel', $validated['class_id']);
            }

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }

            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            $total = $query->count();

            $records = $query
                ->with(['siswa', 'rombel'])
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedRecords = $records->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->id_siswa,
                    'student_name' => $record->siswa->nama ?? null,
                    'class_id' => $record->id_rombel,
                    'tanggal' => $record->tanggal->format('Y-m-d'),
                    'status' => $record->status, // H/S/I/A
                    'notes' => $record->keterangan ?? null
                ];
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedRecords, $page, $limit, $total),
                'Attendance records retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve attendance: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/attendance/{studentId}/month/{month}
     * Get month attendance for student
     * Params: month format like "2025-01"
     */
    public function monthAttendance($studentId, $month)
    {
        try {
            // Validate month format
            $monthParts = explode('-', $month);
            if (count($monthParts) !== 2) {
                return $this->errorResponse('Invalid month format. Use YYYY-MM', 400);
            }

            $year = (int)$monthParts[0];
            $monthNum = (int)$monthParts[1];

            $records = Presensi::where('id_siswa', $studentId)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $monthNum)
                ->with(['siswa', 'rombel'])
                ->orderBy('tanggal', 'ASC')
                ->get();

            if ($records->isEmpty()) {
                return $this->errorResponse('No attendance records found for this month', 404);
            }

            $formattedRecords = $records->map(function ($record) {
                return [
                    'id' => $record->id,
                    'tanggal' => $record->tanggal->format('Y-m-d'),
                    'status' => $record->status,
                    'notes' => $record->keterangan ?? null
                ];
            });

            return $this->successResponse($formattedRecords, 'Month attendance retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve month attendance: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/attendance/stats/{studentId}
     * Get attendance statistics for student
     * Query: ?year_month=2025-01
     */
    public function stats($studentId, Request $request)
    {
        try {
            $validated = $request->validate([
                'year_month' => 'nullable|date_format:Y-m'
            ]);

            $yearMonth = $validated['year_month'] ?? now()->format('Y-m');
            $monthParts = explode('-', $yearMonth);
            $year = (int)$monthParts[0];
            $monthNum = (int)$monthParts[1];

            $records = Presensi::where('id_siswa', $studentId)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $monthNum)
                ->get();

            // Count statuses
            $hadir = $records->where('status', 'H')->count();
            $sakit = $records->where('status', 'S')->count();
            $izin = $records->where('status', 'I')->count();
            $alpa = $records->where('status', 'A')->count();
            $totalDays = $hadir + $sakit + $izin + $alpa;
            $percentage = $totalDays > 0 ? round(($hadir / $totalDays) * 100, 2) : 0;

            // Flag threshold: alpa > 5 atau attendance < 75%
            $isFlagged = ($alpa > 5) || ($percentage < 75);

            $data = [
                'student_id' => $studentId,
                'month' => $yearMonth,
                'total_hadir' => $hadir,
                'total_sakit' => $sakit,
                'total_izin' => $izin,
                'total_alpa' => $alpa,
                'total_days' => $totalDays,
                'attendance_percentage' => $percentage,
                'is_flagged' => $isFlagged,
                'flag_reason' => $isFlagged 
                    ? ($alpa > 5 ? "Alpa > 5 hari ($alpa hari)" : "Attendance < 75% ($percentage%)")
                    : null
            ];

            return $this->successResponse($data, 'Attendance stats retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve stats: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/attendance/flagged
     * Get students flagged for high absence
     * Query: ?threshold=5&month=2025-01
     */
    public function flagged(Request $request)
    {
        try {
            $validated = $request->validate([
                'threshold' => 'nullable|integer|min:1',
                'month' => 'nullable|date_format:Y-m'
            ]);

            $threshold = $validated['threshold'] ?? 5;
            $month = $validated['month'] ?? now()->format('Y-m');
            $monthParts = explode('-', $month);
            $year = (int)$monthParts[0];
            $monthNum = (int)$monthParts[1];

            // Get all students with attendance records in month
            $students = Siswa::where('status', 'Aktif')->get();

            $flaggedStudents = [];

            foreach ($students as $student) {
                $records = Presensi::where('id_siswa', $student->id)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $monthNum)
                    ->get();

                $alpa = $records->where('status', 'A')->count();
                $totalDays = $records->count();
                $percentage = $totalDays > 0 ? ($records->where('status', 'H')->count() / $totalDays) * 100 : 0;

                // Flag if alpa > threshold or attendance < 75%
                if ($alpa > $threshold || $percentage < 75) {
                    $flaggedStudents[] = [
                        'id' => $student->id,
                        'nisn' => $student->nisn,
                        'nama' => $student->nama,
                        'class_id' => $student->id_rombel,
                        'alpa' => $alpa,
                        'attendance_percentage' => round($percentage, 2),
                        'reason' => $alpa > $threshold ? "Alpa > $threshold" : "Attendance < 75%"
                    ];
                }
            }

            return $this->successResponse($flaggedStudents, 'Flagged students retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve flagged students: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/attendance/class/{classId}/date/{date}
     * Get attendance for whole class on date
     * Params: date format like "2025-01-15"
     */
    public function classAttendanceByDate($classId, $date)
    {
        try {
            // Validate date format
            $parsedDate = Carbon::createFromFormat('Y-m-d', $date);

            $records = Presensi::where('id_rombel', $classId)
                ->whereDate('tanggal', $parsedDate)
                ->with(['siswa'])
                ->get();

            $formattedRecords = $records->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->id_siswa,
                    'student_name' => $record->siswa->nama ?? null,
                    'status' => $record->status,
                    'notes' => $record->keterangan ?? null
                ];
            });

            return $this->successResponse($formattedRecords, 'Class attendance for date retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve class attendance: ' . $e->getMessage(), 500);
        }
    }
}
