<?php

namespace App\Http\Controllers\Api;

use App\Models\Walas;

/**
 * WalasTeacherController
 * API endpoints untuk mengelola data walas (guru pengampu kelas)
 */
class WalasTeacherController extends WalasApiController
{
    /**
     * Get all walas (teachers)
     * GET /api/v1/walas/walas
     */
    public function index()
    {
        try {
            $page = request('page', 1);
            $limit = request('per_page', 20);
            $skip = ($page - 1) * $limit;

            $query = Walas::query();

            // Apply filters if provided
            if (request('nama')) {
                $query->where('nama', 'like', '%' . request('nama') . '%');
            }

            if (request('nip')) {
                $query->where('nip', 'like', '%' . request('nip') . '%');
            }

            $total = $query->count();
            $walases = $query->skip($skip)->take($limit)->get();

            return $this->successResponse(
                $this->paginatedResponse($walases, $page, $limit, $total),
                'Walas retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get single walas detail by ID
     * GET /api/v1/walas/walas/{id}
     */
    public function show($id)
    {
        try {
            $walas = Walas::find($id);

            if (!$walas) {
                return $this->errorResponse('Walas not found', 404);
            }

            // Return walas info with related rombel (class)
            $data = [
                'id' => $walas->id,
                'nama' => $walas->nama,
                'jenis_kelamin' => $walas->jenis_kelamin,
                'nip' => $walas->nip,
                'no_wa' => $walas->no_wa,
                'image_url' => $walas->image_url,
                'rombel' => $walas->rombel ? [
                    'id' => $walas->rombel->id,
                    'nama_rombel' => $walas->rombel->nama_rombel,
                    'tingkat' => $walas->rombel->tingkat,
                ] : null,
            ];

            return $this->successResponse($data, 'Walas detail retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search walas by name or NIP
     * GET /api/v1/walas/walas/search?query=nama
     */
    public function search()
    {
        try {
            $query = request('query', '');
            $limit = request('limit', 10);

            if (!$query) {
                return $this->errorResponse('Query parameter is required', 400);
            }

            $walases = Walas::where('nama', 'like', '%' . $query . '%')
                ->orWhere('nip', 'like', '%' . $query . '%')
                ->limit($limit)
                ->get()
                ->map(fn ($w) => [
                    'id' => $w->id,
                    'nama' => $w->nama,
                    'nip' => $w->nip,
                    'jenis_kelamin' => $w->jenis_kelamin,
                ]);

            return $this->successResponse($walases, 'Walas search results');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
