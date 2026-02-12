<?php

namespace App\Http\Controllers\Api;

use App\Models\Keterlambatan;
use App\Models\Siswa;
use Illuminate\Http\Request;

/**
 * SECTION: Keterlambatan (Tardiness) API Endpoints
 * API untuk expose data Keterlambatan dari Walas ke RuangSiswa Kesiswaan
 * Data diambil dari keterlambatan table
 */
class WalasKeterlambatanController extends WalasApiController
{
    /**
     * GET /api/v1/walas/keterlambatan
     * Get all keterlambatan (tardiness) records with optional filters
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
                'limit' => 'nullable|integer|min:1|max:500',
                'page' => 'nullable|integer|min:1'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 50;

            $query = Keterlambatan::query();

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            // Filter by student
            if (!empty($validated['student_id'])) {
                $query->where('siswas_id', $validated['student_id']);
            }

            // Filter by kelas
            if (!empty($validated['kelas'])) {
                $query->where('kelas', $validated['kelas']);
            }

            // Filter by walas
            if (!empty($validated['walas_id'])) {
                $query->where('walas_id', $validated['walas_id']);
            }

            $total = $query->count();

            $tardiness = $query
                ->with('siswa', 'walas', 'siswa.biodata')
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedTardiness = $tardiness->map(function ($record) {
                return $this->formatTardinessData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedTardiness, $page, $limit, $total),
                'Keterlambatan retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/keterlambatan/{id}
     * Get single keterlambatan detail
     */
    public function show($id)
    {
        try {
            $record = Keterlambatan::with('siswa', 'walas', 'siswa.biodata')->findOrFail($id);

            return $this->successResponse(
                $this->formatTardinessData($record),
                'Keterlambatan retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Keterlambatan not found: ' . $e->getMessage(), 404);
        }
    }

    /**
     * GET /api/v1/walas/keterlambatan/student/{studentId}
     * Get all keterlambatan for a specific student
     */
    public function byStudent($studentId, Request $request)
    {
        try {
            // Validate student exists
            $student = Siswa::findOrFail($studentId);

            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = Keterlambatan::where('siswas_id', $studentId);

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            $total = $query->count();

            $tardiness = $query
                ->with('siswa', 'walas', 'siswa.biodata')
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedTardiness = $tardiness->map(function ($record) {
                return $this->formatTardinessData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedTardiness, $page, $limit, $total),
                'Keterlambatan for student retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/keterlambatan/kelas/{kelas}
     * Get all keterlambatan for a specific kelas (class)
     */
    public function byKelas($kelas, Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = Keterlambatan::where('kelas', $kelas);

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            $total = $query->count();

            $tardiness = $query
                ->with('siswa', 'walas', 'siswa.biodata')
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedTardiness = $tardiness->map(function ($record) {
                return $this->formatTardinessData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedTardiness, $page, $limit, $total),
                'Keterlambatan for kelas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve kelas keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/keterlambatan/walas/{walasId}
     * Get all keterlambatan reported by a specific walas
     */
    public function byWalas($walasId, Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'kelas' => 'nullable|string',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = Keterlambatan::where('walas_id', $walasId);

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            // Filter by kelas
            if (!empty($validated['kelas'])) {
                $query->where('kelas', $validated['kelas']);
            }

            $total = $query->count();

            $tardiness = $query
                ->with('siswa', 'walas', 'siswa.biodata')
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedTardiness = $tardiness->map(function ($record) {
                return $this->formatTardinessData($record);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedTardiness, $page, $limit, $total),
                'Keterlambatan by walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve walas keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/walas/keterlambatan
     * Create new keterlambatan record
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'walas_id' => 'required|integer|exists:walas,id',
                'siswas_id' => 'required|integer|exists:siswa,id',
                'kelas' => 'required|string',
                'tanggal' => 'required|date',
                'jam_masuk' => 'required|date_format:H:i:s',
                'menit_terlambat' => 'required|integer|min:1',
                'alasan' => 'nullable|string',
                'keterangan' => 'nullable|string',
            ]);

            // Get student data
            $siswa = Siswa::findOrFail($validated['siswas_id']);

            $keterlambatan = Keterlambatan::create([
                'walas_id' => $validated['walas_id'],
                'siswas_id' => $validated['siswas_id'],
                'kelas' => $validated['kelas'],
                'tanggal' => $validated['tanggal'],
                'jam_masuk' => $validated['jam_masuk'],
                'menit_terlambat' => $validated['menit_terlambat'],
                'alasan' => $validated['alasan'] ?? '',
                'keterangan' => $validated['keterangan'] ?? '',
            ]);

            return $this->successResponse(
                $this->formatTardinessData($keterlambatan),
                'Keterlambatan created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * PUT/PATCH /api/v1/walas/keterlambatan/{id}
     * Update keterlambatan record
     */
    public function update($id, Request $request)
    {
        try {
            $keterlambatan = Keterlambatan::findOrFail($id);

            $validated = $request->validate([
                'jam_masuk' => 'nullable|date_format:H:i:s',
                'menit_terlambat' => 'nullable|integer|min:1',
                'alasan' => 'nullable|string',
                'keterangan' => 'nullable|string',
            ]);

            $keterlambatan->update($validated);

            return $this->successResponse(
                $this->formatTardinessData($keterlambatan),
                'Keterlambatan updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/v1/walas/keterlambatan/{id}
     * Delete keterlambatan record
     */
    public function destroy($id)
    {
        try {
            $keterlambatan = Keterlambatan::findOrFail($id);
            $keterlambatan->delete();

            return $this->successResponse(
                null,
                'Keterlambatan deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete keterlambatan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/keterlambatan/stats
     * Get statistics of keterlambatan
     */
    public function stats(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'student_id' => 'nullable|integer',
                'kelas' => 'nullable|string',
            ]);

            $query = Keterlambatan::query();

            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }
            if (!empty($validated['student_id'])) {
                $query->where('siswas_id', $validated['student_id']);
            }
            if (!empty($validated['kelas'])) {
                $query->where('kelas', $validated['kelas']);
            }

            $totalTardiness = $query->count();
            $totalMinutes = $query->sum('menit_terlambat');
            $averageMinutes = $totalTardiness > 0 ? round($totalMinutes / $totalTardiness) : 0;

            // Top offenders
            $topOffenders = Keterlambatan::query()
                ->select('siswas_id')
                ->addSelect(\DB::raw('COUNT(*) as tardiness_count'))
                ->addSelect(\DB::raw('SUM(menit_terlambat) as total_minutes'))
                ->groupBy('siswas_id')
                ->orderBy('tardiness_count', 'DESC')
                ->limit(10)
                ->with('siswa')
                ->get()
                ->map(function ($item) {
                    return [
                        'student_id' => $item->siswas_id,
                        'student_name' => $item->siswa->nama ?? 'Unknown',
                        'tardiness_count' => $item->tardiness_count,
                        'total_minutes' => $item->total_minutes,
                    ];
                });

            return $this->successResponse([
                'total_tardiness' => $totalTardiness,
                'total_minutes' => $totalMinutes,
                'average_minutes' => $averageMinutes,
                'top_offenders' => $topOffenders
            ], 'Keterlambatan statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve stats: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Format tardiness data
     */
    private function formatTardinessData($record)
    {
        $biodata = $record->siswa->biodata ?? null;

        return [
            'id' => $record->id,
            'siswas_id' => $record->siswas_id,
            'siswas_name' => $record->siswa->nama ?? null,
            'kelas' => $record->kelas,
            'walas_id' => $record->walas_id,
            'walas_name' => $record->walas->nama ?? null,
            'tanggal' => $record->tanggal ? $record->tanggal->format('Y-m-d') : null,
            'jam_masuk' => $record->jam_masuk,
            'menit_terlambat' => $record->menit_terlambat,
            'alasan' => $record->alasan,
            'keterangan' => $record->keterangan,
            'parent_data' => [
                'ayah' => [
                    'nama_ayah' => $biodata->nama_ayah ?? null,
                    'no_wa_ayah' => $biodata->no_wa_ayah ?? null,
                    'siswas_id' => $record->siswas_id
                ],
                'ibu' => [
                    'nama_ibu' => $biodata->nama_ibu ?? null,
                    'no_wa_ibu' => $biodata->no_wa_ibu ?? null,
                    'siswas_id' => $record->siswas_id
                ]
            ],
            'created_at' => $record->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $record->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
