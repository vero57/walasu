<?php

namespace App\Http\Controllers\Api;

use App\Models\DetailPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

/**
 * SECTION: Kehadiran (Attendance) API Endpoints
 * API untuk expose data Kehadiran dari Walas ke RuangSiswa Kesiswaan
 * Data diambil dari detail_presensi dan presensi tables
 */
class WalaskehadiranController extends WalasApiController
{
    /**
     * GET /api/v1/walas/kehadiran
     * Get all kehadiran (attendance) records with optional filters
     * Query: ?start_date=2025-01-01&end_date=2025-01-31&student_id=X&kelas=X&walas_id=Y&limit=100&page=1
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'student_id' => 'nullable|integer',
                'kelas' => 'nullable|string',
                'walas_id' => 'nullable|integer',
                'status' => 'nullable|string|in:hadir,izin,sakit,alfa',
                'limit' => 'nullable|integer|min:1|max:500',
                'page' => 'nullable|integer|min:1'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 50;

            $query = DetailPresensi::query();

            // Join with presensi to access tanggal and kelas
            $query->join('presensis', 'detail_presensis.presensis_id', '=', 'presensis.id');

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('presensis.tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('presensis.tanggal', '<=', $validated['end_date']);
            }

            // Filter by student
            if (!empty($validated['student_id'])) {
                $query->where('detail_presensis.siswas_id', $validated['student_id']);
            }

            // Filter by kelas
            if (!empty($validated['kelas'])) {
                $query->where('presensis.kelas', $validated['kelas']);
            }

            // Filter by walas
            if (!empty($validated['walas_id'])) {
                $query->where('presensis.walas_id', $validated['walas_id']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('detail_presensis.status', $validated['status']);
            }

            $total = $query->count();

            $attendance = $query
                ->with('presensi', 'siswa')
                ->select('detail_presensis.*', 'presensis.tanggal', 'presensis.kelas')
                ->orderBy('presensis.tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedAttendance = $attendance->map(function ($record) {
                return $this->formatAttendanceData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedAttendance, $page, $limit, $total),
                'Kehadiran retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve kehadiran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/kehadiran/{id}
     * Get single kehadiran detail
     */
    public function show($id)
    {
        try {
            $record = DetailPresensi::with('presensi', 'siswa')->findOrFail($id);

            return $this->successResponse(
                $this->formatAttendanceData($record),
                'Kehadiran retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Kehadiran not found: ' . $e->getMessage(), 404);
        }
    }

    /**
     * GET /api/v1/walas/kehadiran/student/{studentId}
     * Get all kehadiran for a specific student
     */
    public function byStudent($studentId, Request $request)
    {
        try {
            // Validate student exists
            $student = Siswa::findOrFail($studentId);

            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'status' => 'nullable|string|in:hadir,izin,sakit,alfa',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = DetailPresensi::where('siswas_id', $studentId);

            // Join with presensi for date range filtering
            $query->join('presensis', 'detail_presensis.presensis_id', '=', 'presensis.id');

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('presensis.tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('presensis.tanggal', '<=', $validated['end_date']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('detail_presensis.status', $validated['status']);
            }

            $total = $query->count();

            $attendance = $query
                ->with('presensi', 'siswa')
                ->select('detail_presensis.*', 'presensis.tanggal', 'presensis.kelas')
                ->orderBy('presensis.tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedAttendance = $attendance->map(function ($record) {
                return $this->formatAttendanceData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedAttendance, $page, $limit, $total),
                'Kehadiran for student retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student kehadiran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/kehadiran/kelas/{kelas}
     * Get all kehadiran for a specific kelas (class)
     */
    public function byKelas($kelas, Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'status' => 'nullable|string|in:hadir,izin,sakit,alfa',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = DetailPresensi::query()
                ->join('presensis', 'detail_presensis.presensis_id', '=', 'presensis.id')
                ->where('presensis.kelas', $kelas);

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('presensis.tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('presensis.tanggal', '<=', $validated['end_date']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('detail_presensis.status', $validated['status']);
            }

            $total = $query->count();

            $attendance = $query
                ->with('presensi', 'siswa')
                ->select('detail_presensis.*', 'presensis.tanggal', 'presensis.kelas')
                ->orderBy('presensis.tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedAttendance = $attendance->map(function ($record) {
                return $this->formatAttendanceData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedAttendance, $page, $limit, $total),
                'Kehadiran for kelas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve kelas kehadiran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/kehadiran/walas/{walasId}
     * Get all kehadiran reported by a specific walas
     */
    public function byWalas($walasId, Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'kelas' => 'nullable|string',
                'status' => 'nullable|string|in:hadir,izin,sakit,alfa',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = DetailPresensi::query()
                ->join('presensis', 'detail_presensis.presensis_id', '=', 'presensis.id')
                ->where('presensis.walas_id', $walasId);

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('presensis.tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('presensis.tanggal', '<=', $validated['end_date']);
            }

            // Filter by kelas
            if (!empty($validated['kelas'])) {
                $query->where('presensis.kelas', $validated['kelas']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('detail_presensis.status', $validated['status']);
            }

            $total = $query->count();

            $attendance = $query
                ->with('presensi', 'siswa')
                ->select('detail_presensis.*', 'presensis.tanggal', 'presensis.kelas')
                ->orderBy('presensis.tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedAttendance = $attendance->map(function ($record) {
                return $this->formatAttendanceData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedAttendance, $page, $limit, $total),
                'Kehadiran by walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve walas kehadiran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Format attendance data for API response
     * Returns: presensi_id, siswas_id, status, kelas, tanggal
     */
    private function formatAttendanceData($record)
    {
        return [
            'detail_presensi_id' => $record->id,
            'presensi_id' => $record->presensis_id,
            'siswas_id' => $record->siswas_id,
            'status' => $record->status,
            'kelas' => $record->presensi->kelas ?? null,
            'tanggal' => $record->presensi->tanggal ?? null,
            'walas_id' => $record->presensi->walas_id ?? null,
            'student_name' => $record->siswa->nama ?? null,
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at
        ];
    }
}
