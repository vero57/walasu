<?php

namespace App\Http\Controllers\Api;

use App\Models\HomeVisit;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class WalasHomeVisitController extends WalasApiController
{
    /**
     * Get all home visits with pagination and filters
     * GET /api/v1/walas/home-visits
     * 
     * Query Parameters:
     * - page (int): Page number, default 1
     * - per_page (int): Items per page, default 20
     * - student_id (int): Filter by student ID
     * - walas_id (int): Filter by Walas/teacher ID (whoever conducted visit)
     * - status (string): Filter by visit status (planned/completed/canceled)
     * - class_id (int): Filter by class
     * - date_from (date): Filter from date (YYYY-MM-DD)
     * - date_to (date): Filter to date (YYYY-MM-DD)
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $query = HomeVisit::with(['siswa', 'walas'])
                ->orderBy('tanggal_kunjungan', 'desc');

            // Apply filters
            if ($request->has('student_id')) {
                $query->where('siswa_id', $request->student_id);
            }

            if ($request->has('walas_id')) {
                $query->where('walas_id', $request->walas_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->date_to);
            }

            $total = $query->count();
            $homeVisits = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->paginatedResponse(
                $homeVisits->items(),
                $homeVisits->currentPage(),
                $homeVisits->perPage(),
                $total,
                'Home visits retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve home visits: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get single home visit detail
     * GET /api/v1/walas/home-visits/{id}
     */
    public function show($id)
    {
        try {
            $homeVisit = HomeVisit::with(['siswa', 'walas'])
                ->findOrFail($id);

            return $this->successResponse($homeVisit, 'Home visit details retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse('Home visit not found: ' . $e->getMessage(), 404);
        }
    }

    /**
     * Get home visits for specific student
     * GET /api/v1/walas/home-visits/student/{studentId}
     * 
     * Query Parameters:
     * - limit (int): Number of records, default 50
     * - status (string): Filter by status
     */
    public function byStudent($studentId, Request $request)
    {
        try {
            $limit = $request->get('limit', 50);

            $query = HomeVisit::where('siswa_id', $studentId)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_kunjungan', 'desc');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $homeVisits = $query->limit($limit)->get();

            return $this->successResponse(
                $homeVisits,
                'Home visits for student retrieved successfully',
                ['total' => count($homeVisits)]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student home visits: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get home visits conducted by specific Walas/teacher
     * GET /api/v1/walas/home-visits/walas/{walasId}
     * 
     * Query Parameters:
     * - page (int): Page number
     * - per_page (int): Items per page, default 20
     * - date_from (date): Filter from date
     * - date_to (date): Filter to date
     */
    public function byWalas($walasId, Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $query = HomeVisit::where('walas_id', $walasId)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_kunjungan', 'desc');

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->date_to);
            }

            $total = $query->count();
            $homeVisits = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->paginatedResponse(
                $homeVisits->items(),
                $homeVisits->currentPage(),
                $homeVisits->perPage(),
                $total,
                'Home visits by Walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve Walas home visits: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get home visits on specific date for whole class
     * GET /api/v1/walas/home-visits/class/{classId}/date/{date}
     * 
     * Date format: YYYY-MM-DD
     */
    public function byClassDate($classId, $date)
    {
        try {
            $homeVisits = HomeVisit::whereHas('siswa', function ($q) use ($classId) {
                $q->where('rombel_id', $classId);
            })
                ->whereDate('tanggal_kunjungan', $date)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal_kunjungan')
                ->get();

            return $this->successResponse(
                $homeVisits,
                'Class home visits for date retrieved successfully',
                ['total' => count($homeVisits)]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve class home visits: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get home visit statistics
     * GET /api/v1/walas/home-visits/stats
     * 
     * Query Parameters:
     * - class_id (int): Filter by class
     * - date_from (date): From date
     * - date_to (date): To date
     */
    public function stats(Request $request)
    {
        try {
            $query = HomeVisit::query();

            if ($request->has('class_id')) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('rombel_id', request('class_id'));
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->date_to);
            }

            $stats = [
                'total_visits' => $query->count(),
                'completed_visits' => (clone $query)->where('status', 'completed')->count(),
                'planned_visits' => (clone $query)->where('status', 'planned')->count(),
                'canceled_visits' => (clone $query)->where('status', 'canceled')->count(),
                'visits_by_month' => $this->getVisitsByMonth($request),
            ];

            return $this->successResponse($stats, 'Home visit statistics retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Get visits grouped by month
     */
    private function getVisitsByMonth(Request $request)
    {
        $query = HomeVisit::query();

        if ($request->has('class_id')) {
            $query->whereHas('siswa', function ($q) {
                $q->where('rombel_id', request('class_id'));
            });
        }

        // Last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $count = (clone $query)
                ->whereYear('tanggal_kunjungan', now()->subMonths($i)->year)
                ->whereMonth('tanggal_kunjungan', now()->subMonths($i)->month)
                ->count();

            $monthData[$month] = $count;
        }

        return $monthData;
    }
}
