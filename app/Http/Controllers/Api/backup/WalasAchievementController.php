<?php

namespace App\Http\Controllers\Api;

use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class WalasAchievementController extends WalasApiController
{
    /**
     * Get all achievements with pagination and filters
     * GET /api/v1/walas/achievements
     * 
     * Query Parameters:
     * - page (int): Page number, default 1
     * - per_page (int): Items per page, default 20
     * - student_id (int): Filter by student ID
     * - walas_id (int): Filter by creator Walas/teacher ID
     * - type (string): Filter by type (academic/non-academic/sports/arts/leadership)
     * - category_id (int): Filter by category
     * - class_id (int): Filter by class
     * - date_from (date): From date (YYYY-MM-DD)
     * - date_to (date): To date (YYYY-MM-DD)
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $query = PrestasiSiswa::with(['siswa', 'walas'])
                ->orderBy('tanggal_prestasi', 'desc');

            // Apply filters
            if ($request->has('student_id')) {
                $query->where('siswa_id', $request->student_id);
            }

            if ($request->has('walas_id')) {
                $query->where('walas_id', $request->walas_id);
            }

            if ($request->has('type')) {
                $query->where('tipe_prestasi', $request->type);
            }

            if ($request->has('category_id')) {
                $query->where('kategori_prestasi_id', $request->category_id);
            }

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_prestasi', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_prestasi', '<=', $request->date_to);
            }

            $total = $query->count();
            $achievements = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->paginatedResponse(
                $achievements->items(),
                $achievements->currentPage(),
                $achievements->perPage(),
                $total,
                'Achievements retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve achievements: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get single achievement detail
     * GET /api/v1/walas/achievements/{id}
     */
    public function show($id)
    {
        try {
            $achievement = PrestasiSiswa::with(['siswa', 'walas'])
                ->findOrFail($id);

            return $this->successResponse($achievement, 'Achievement details retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse('Achievement not found: ' . $e->getMessage(), 404);
        }
    }

    /**
     * Get achievements for specific student
     * GET /api/v1/walas/achievements/student/{studentId}
     * 
     * Query Parameters:
     * - type (string): Filter by achievement type
     * - limit (int): Number of records, default 100
     */
    public function byStudent($studentId, Request $request)
    {
        try {
            $limit = $request->get('limit', 100);

            $query = PrestasiSiswa::where('siswa_id', $studentId)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_prestasi', 'desc');

            if ($request->has('type')) {
                $query->where('tipe_prestasi', $request->type);
            }

            $achievements = $query->limit($limit)->get();

            return $this->successResponse(
                $achievements,
                'Student achievements retrieved successfully',
                ['total' => count($achievements)]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student achievements: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get achievements by type (academic, sports, arts, leadership, etc)
     * GET /api/v1/walas/achievements/type/{type}
     * 
     * Types: academic, non-academic, sports, arts, leadership, community_service, entrepreneurship
     * 
     * Query Parameters:
     * - page (int): Page number
     * - per_page (int): Items per page, default 20
     * - class_id (int): Filter by class
     * - date_from (date): From date
     * - date_to (date): To date
     */
    public function byType($type, Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $query = PrestasiSiswa::where('tipe_prestasi', $type)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_prestasi', 'desc');

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_prestasi', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_prestasi', '<=', $request->date_to);
            }

            $total = $query->count();
            $achievements = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->paginatedResponse(
                $achievements->items(),
                $achievements->currentPage(),
                $achievements->perPage(),
                $total,
                'Achievements by type retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve achievements by type: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get achievements added by specific Walas/teacher
     * GET /api/v1/walas/achievements/walas/{walasId}
     * 
     * Query Parameters:
     * - page (int): Page number
     * - per_page (int): Items per page, default 20
     * - date_from (date): From date
     * - date_to (date): To date
     */
    public function byWalas($walasId, Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $query = PrestasiSiswa::where('walas_id', $walasId)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_prestasi', 'desc');

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_prestasi', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_prestasi', '<=', $request->date_to);
            }

            $total = $query->count();
            $achievements = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->paginatedResponse(
                $achievements->items(),
                $achievements->currentPage(),
                $achievements->perPage(),
                $total,
                'Achievements by Walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve Walas achievements: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get achievement statistics
     * GET /api/v1/walas/achievements/stats
     * 
     * Query Parameters:
     * - class_id (int): Filter by class
     * - date_from (date): From date
     * - date_to (date): To date
     */
    public function stats(Request $request)
    {
        try {
            $query = PrestasiSiswa::query();

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_prestasi', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_prestasi', '<=', $request->date_to);
            }

            $stats = [
                'total_achievements' => $query->count(),
                'by_type' => [
                    'academic' => (clone $query)->where('tipe_prestasi', 'academic')->count(),
                    'non_academic' => (clone $query)->where('tipe_prestasi', 'non-academic')->count(),
                    'sports' => (clone $query)->where('tipe_prestasi', 'sports')->count(),
                    'arts' => (clone $query)->where('tipe_prestasi', 'arts')->count(),
                    'leadership' => (clone $query)->where('tipe_prestasi', 'leadership')->count(),
                ],
                'achievements_by_month' => $this->getAchievementsByMonth($request),
            ];

            return $this->successResponse($stats, 'Achievement statistics retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Get achievements grouped by type
     */
    public function byTypeStats(Request $request)
    {
        try {
            $query = PrestasiSiswa::query();

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            $types = ['academic', 'non-academic', 'sports', 'arts', 'leadership'];
            $typeStats = [];

            foreach ($types as $type) {
                $typeStats[$type] = (clone $query)->where('tipe_prestasi', $type)->count();
            }

            return $this->successResponse($typeStats, 'Achievement type breakdown retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve type statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Get achievements grouped by month
     */
    private function getAchievementsByMonth(Request $request)
    {
        $query = PrestasiSiswa::query();

        if ($request->has('class_id')) {
            $query->whereHas('siswa', function ($q) {
                $q->where('rombel_id', request('class_id'));
            });
        }

        // Last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $count = (clone $query)
                ->whereYear('tanggal_prestasi', now()->subMonths($i)->year)
                ->whereMonth('tanggal_prestasi', now()->subMonths($i)->month)
                ->count();

            $monthData[$month] = $count;
        }

        return $monthData;
    }
}
