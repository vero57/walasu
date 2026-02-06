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
     * GET /api/v1/walas/case-notes/{studentId}
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

            $query = CatatanKasusSiswa::where('id_siswa', $studentId)
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
                    'student_id' => $note->id_siswa,
                    'student_name' => $note->siswa->nama ?? null,
                    'walas_id' => $note->id_guru,
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

            $query = CatatanKasusSiswa::where('id_guru', $walasId)
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
                    'student_id' => $note->id_siswa,
                    'student_name' => $note->siswa->nama ?? null,
                    'walas_id' => $note->id_guru,
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

            $notes = CatatanKasusSiswa::where('id_siswa', $studentId)
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
                    'student_id' => $note->id_siswa,
                    'walas_id' => $note->id_guru,
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
