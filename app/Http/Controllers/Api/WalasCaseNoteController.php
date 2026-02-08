<?php

namespace App\Http\Controllers\Api;

use App\Models\CatatanKasusSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

/**
 * SECTION C: Case Notes API Endpoints
 * API untuk expose data Catatan Kasus Siswa dari Walas ke Kesiswaan
 */
class WalasCaseNoteController extends WalasApiController
{
    /**
     * GET /api/v1/walas/case-notes
     * Get all case notes with filters (date range, walas, etc)
     * Query: ?start_date=2025-01-01&end_date=2025-01-31&walas_id=X&limit=100&page=1
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'walas_id' => 'nullable|integer',
                'limit' => 'nullable|integer|min:1|max:500',
                'page' => 'nullable|integer|min:1'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 100;

            $query = CatatanKasusSiswa::query();

            // Filter by date range
            if (!empty($validated['start_date'])) {
                $query->whereDate('tanggal', '>=', $validated['start_date']);
            }
            if (!empty($validated['end_date'])) {
                $query->whereDate('tanggal', '<=', $validated['end_date']);
            }

            // Filter by walas
            if (!empty($validated['walas_id'])) {
                $query->where('walas_id', $validated['walas_id']);
            }

            $total = $query->count();

            $notes = $query
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal', 'DESC')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedNotes = $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'student_id' => $note->siswas_id,
                    'student_name' => $note->siswa->nama ?? null,
                    'walas_id' => $note->walas_id,
                    'walas_name' => $note->walas->nama ?? null,
                    'tanggal' => $note->tanggal->format('Y-m-d'),
                    'keterangan' => $note->keterangan,
                    'tindakan' => $note->tindakan ?? null,
                    'status' => $note->status ?? 'Active',
                    'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $note->updated_at->format('Y-m-d H:i:s')
                ];
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedNotes, $page, $limit, $total),
                'Case notes retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve case notes: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/case-notes/byStudent/{studentId}
     * Get all case notes for a student
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

            $query = CatatanKasusSiswa::where('siswas_id', $studentId)
                ->orderBy('tanggal', 'DESC');

            $total = $query->count();

            $notes = $query
                ->with(['siswa', 'walas'])
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedNotes = $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'student_id' => $note->siswas_id,
                    'student_name' => $note->siswa->nama ?? null,
                    'walas_id' => $note->walas_id,
                    'walas_name' => $note->walas->nama ?? null,
                    'tanggal' => $note->tanggal->format('Y-m-d'),
                    'keterangan' => $note->keterangan,
                    'tindakan' => $note->tindakan ?? null,
                    'status' => $note->status ?? 'Active',
                    'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $note->updated_at->format('Y-m-d H:i:s')
                ];
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedNotes, $page, $limit, $total),
                'Case notes retrieved successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Student not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve case notes: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/case-notes/walas/{walasId}
     * Get all case notes by specific walas
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

            $query = CatatanKasusSiswa::where('walas_id', $walasId)
                ->orderBy('tanggal', 'DESC');

            $total = $query->count();

            $notes = $query
                ->with(['siswa', 'walas'])
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedNotes = $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'student_id' => $note->siswas_id,
                    'student_name' => $note->siswa->nama ?? null,
                    'walas_id' => $note->walas_id,
                    'tanggal' => $note->tanggal->format('Y-m-d'),
                    'keterangan' => $note->keterangan,
                    'tindakan' => $note->tindakan ?? null,
                    'status' => $note->status ?? 'Active',
                    'created_at' => $note->created_at->format('Y-m-d H:i:s')
                ];
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedNotes, $page, $limit, $total),
                'Case notes retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve case notes: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/case-notes/{studentId}/recent
     * Get recent case notes for student
     * Query: ?limit=5
     */
    public function recent($studentId, Request $request)
    {
        try {
            $validated = $request->validate([
                'limit' => 'nullable|integer|min:1|max:50'
            ]);

            $limit = $validated['limit'] ?? 5;

            $notes = CatatanKasusSiswa::where('siswas_id', $studentId)
                ->with(['siswa', 'walas'])
                ->orderBy('tanggal', 'DESC')
                ->take($limit)
                ->get();

            if ($notes->isEmpty()) {
                return $this->errorResponse('No case notes found for this student', 404);
            }

            $formattedNotes = $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'student_id' => $note->siswas_id,
                    'walas_id' => $note->walas_id,
                    'walas_name' => $note->walas->nama ?? null,
                    'tanggal' => $note->tanggal->format('Y-m-d'),
                    'keterangan' => $note->keterangan,
                    'tindakan' => $note->tindakan ?? null,
                    'created_at' => $note->created_at->format('Y-m-d H:i:s')
                ];
            });

            return $this->successResponse($formattedNotes, 'Recent case notes retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve recent case notes: ' . $e->getMessage(), 500);
        }
    }
}
