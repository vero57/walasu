<?php

namespace App\Http\Controllers\Api;

use App\Models\CatatanKasusSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

/**
 * SECTION: Pelanggaran API Endpoints
 * API untuk expose data Pelanggaran dari Walas ke RuangSiswa Kesiswaan
 * Data diambil dari catatan_kasus_siswas dan diteruskan ke backend point-pelanggaran
 */
class WalasPelanggaranController extends WalasApiController
{
    /**
     * GET /api/v1/walas/pelanggaran
     * Get all pelanggaran (violations) from case notes
     * Query: ?start_date=2025-01-01&end_date=2025-01-31&student_id=X&walas_id=Y&limit=100&page=1
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'student_id' => 'nullable|integer',
                'walas_id' => 'nullable|integer',
                'limit' => 'nullable|integer|min:1|max:500',
                'page' => 'nullable|integer|min:1'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 50;

            $query = CatatanKasusSiswa::query();

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

            // Filter by walas
            if (!empty($validated['walas_id'])) {
                $query->where('walas_id', $validated['walas_id']);
            }

            $total = $query->count();

            $violations = $query
                ->with(['siswa', 'siswa.biodata', 'walas'])
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedViolations = $violations->map(function ($violation) {
                return $this->formatViolationData($violation);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedViolations, $page, $limit, $total),
                'Pelanggaran retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve pelanggaran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/pelanggaran/{id}
     * Get single pelanggaran detail
     */
    public function show($id)
    {
        try {
            $violation = CatatanKasusSiswa::with(['siswa', 'siswa.biodata', 'walas'])->findOrFail($id);

            return $this->successResponse(
                $this->formatViolationData($violation),
                'Pelanggaran retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Pelanggaran not found: ' . $e->getMessage(), 404);
        }
    }

    /**
     * GET /api/v1/walas/pelanggaran/student/{studentId}
     * Get all pelanggaran for a specific student
     */
    public function byStudent($studentId, Request $request)
    {
        try {
            // Validate student exists
            $student = Siswa::findOrFail($studentId);

            $validated = $request->validate([
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = CatatanKasusSiswa::where('siswas_id', $studentId);
            $total = $query->count();

            $violations = $query
                ->with(['siswa', 'siswa.biodata', 'walas'])
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedViolations = $violations->map(function ($violation) {
                return $this->formatViolationData($violation);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedViolations, $page, $limit, $total),
                'Pelanggaran for student retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student pelanggaran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/pelanggaran/walas/{walasId}
     * Get all pelanggaran reported by a specific walas
     */
    public function byWalas($walasId, Request $request)
    {
        try {
            $validated = $request->validate([
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1'
            ]);

            $limit = $validated['limit'] ?? 20;
            $page = $validated['page'] ?? 1;

            $query = CatatanKasusSiswa::where('walas_id', $walasId);
            $total = $query->count();

            $violations = $query
                ->with(['siswa', 'siswa.biodata', 'walas'])
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedViolations = $violations->map(function ($violation) {
                return $this->formatViolationData($violation);
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedViolations, $page, $limit, $total),
                'Pelanggaran by walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve walas pelanggaran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/walas/pelanggaran/sync
     * Sync pelanggaran to RuangSiswa backend point-pelanggaran system
     * Body: { student_id, kasus, tindak_lanjut, keterangan, kode?, bobot?, category_point? }
     */
    public function sync(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer',
                'kasus' => 'required|string',
                'tindak_lanjut' => 'required|string',
                'keterangan' => 'nullable|string',
                'kode' => 'nullable|integer',
                'bobot' => 'nullable|integer|min:1|max:100',
                'category_point' => 'nullable|string',
                'walas_id' => 'nullable|integer'
            ]);

            // Get student data
            $student = Siswa::findOrFail($validated['student_id']);

            // Create case note in Walas (if walas_id provided)
            if (!empty($validated['walas_id'])) {
                CatatanKasusSiswa::create([
                    'walas_id' => $validated['walas_id'],
                    'siswas_id' => $validated['student_id'],
                    'kasus' => $validated['kasus'],
                    'tindak_lanjut' => $validated['tindak_lanjut'],
                    'keterangan' => $validated['keterangan'] ?? '',
                    'tanggal' => now()
                ]);
            }

            // Prepare data to sync to RuangSiswa Backend
            $syncData = [
                'student_id' => $validated['student_id'],
                'student_name' => $student->nama,
                'class_id' => $student->rombels_id,
                'kasus' => $validated['kasus'],
                'tindak_lanjut' => $validated['tindak_lanjut'],
                'keterangan' => $validated['keterangan'] ?? '',
                'kode' => $validated['kode'] ?? null,
                'bobot' => $validated['bobot'] ?? null,
                'category_point' => $validated['category_point'] ?? 'Pelanggaran',
                'tanggal_pelanggaran' => now()->format('Y-m-d'),
                'severity' => $this->calculateSeverity($validated['bobot'] ?? 0),
                'source' => 'walas'
            ];

            // Forward to RuangSiswa Backend
            $syncResult = $this->syncToRuangSiswaBE($syncData);

            if (!$syncResult['success']) {
                return $this->errorResponse(
                    'Failed to sync pelanggaran to RuangSiswa: ' . ($syncResult['error'] ?? 'Unknown error'),
                    500
                );
            }

            return $this->successResponse(
                [
                    'case_note_created' => !empty($validated['walas_id']),
                    'synced_to_backend' => true,
                    'backend_response' => $syncResult['body']
                ],
                'Pelanggaran synced successfully to RuangSiswa'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to sync pelanggaran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/pelanggaran/stats
     * Get statistics of pelanggaran
     */
    public function stats(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'student_id' => 'nullable|integer'
            ]);

            $query = CatatanKasusSiswa::query();

            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }
            if (!empty($validated['student_id'])) {
                $query->where('siswas_id', $validated['student_id']);
            }

            $totalViolations = $query->count();
            $uniqueStudents = $query->distinct('siswas_id')->count('siswas_id');

            // Top violating students
            $topViolators = CatatanKasusSiswa::query()
                ->select('siswas_id')
                ->addSelect(\DB::raw('COUNT(*) as violation_count'))
                ->groupBy('siswas_id')
                ->orderBy('violation_count', 'DESC')
                ->limit(10)
                ->with('siswa')
                ->get()
                ->map(function ($item) {
                    return [
                        'student_id' => $item->siswas_id,
                        'student_name' => $item->siswa->nama ?? 'Unknown',
                        'violation_count' => $item->violation_count
                    ];
                });

            return $this->successResponse([
                'total_violations' => $totalViolations,
                'unique_students' => $uniqueStudents,
                'top_violators' => $topViolators
            ], 'Pelanggaran statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve stats: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Format violation data
     */
    private function formatViolationData($violation)
    {
        $biodata = $violation->siswa->biodata ?? null;
        
        return [
            'id' => $violation->id,
            'siswas_id' => $violation->siswas_id,
            'siswas_name' => $violation->siswa->nama ?? null,
            'class_id' => $violation->siswa->rombels_id ?? null,
            'class_name' => $violation->siswa->rombel->nama_kelas ?? null,
            'walas_id' => $violation->walas_id,
            'walas_name' => $violation->walas->nama ?? null,
            'tanggal_pembinaan' => $violation->tanggal ? $violation->tanggal->format('Y-m-d') : null,
            'kasus' => $violation->kasus,
            'tindak_lanjut' => $violation->tindak_lanjut,
            'keterangan' => $violation->keterangan,
            'severity' => $this->determineSeverity($violation->kasus),
            'parent_data' => [
                'ayah' => [
                    'nama_ayah' => $biodata->nama_ayah ?? null,
                    'no_wa_ayah' => $biodata->no_wa_ayah ?? null,
                    'siswas_id' => $violation->siswas_id
                ],
                'ibu' => [
                    'nama_ibu' => $biodata->nama_ibu ?? null,
                    'no_wa_ibu' => $biodata->no_wa_ibu ?? null,
                    'siswas_id' => $violation->siswas_id
                ]
            ],
            'created_at' => $violation->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $violation->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Helper: Determine severity level from kasus
     */
    private function determineSeverity($kasus)
    {
        // 1 = minor, 2 = moderate, 3 = severe
        $kasusLower = strtolower($kasus);

        if (strpos($kasusLower, 'bolos') !== false || 
            strpos($kasusLower, 'merokok') !== false || 
            strpos($kasusLower, 'narkoba') !== false) {
            return 3; // Severe
        }

        if (strpos($kasusLower, 'terlambat') !== false || 
            strpos($kasusLower, 'berbicara') !== false) {
            return 1; // Minor
        }

        return 2; // Moderate (default)
    }

    /**
     * Helper: Calculate severity from bobot
     */
    private function calculateSeverity($bobot)
    {
        if ($bobot >= 50) return 3; // Severe
        if ($bobot >= 25) return 2; // Moderate
        return 1; // Minor
    }

    /**
     * Helper: Sync data to RuangSiswa Backend
     */
    private function syncToRuangSiswaBE($data)
    {
        try {
            $baseUrl = config('services.ruang_siswa.base_url', 'http://localhost:3001/api');
            $apiKey = config('services.ruang_siswa.api_key', '');

            $url = rtrim($baseUrl, '/') . '/violations'; // or appropriate endpoint

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];

            if (!empty($apiKey)) {
                $headers['Authorization'] = "Bearer {$apiKey}";
            }

            $client = new \GuzzleHttp\Client();

            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data,
                'timeout' => 10
            ]);

            return [
                'success' => $response->getStatusCode() >= 200 && $response->getStatusCode() < 300,
                'body' => json_decode($response->getBody(), true)
            ];
        } catch (\Exception $e) {
            \Log::error("RuangSiswa BE API call error: {$e->getMessage()}");
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
