<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\BioSiswa;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * SECTION A: Student & Class API Endpoints
 * API untuk expose data Siswa dan Rombel dari Walas ke Kesiswaan
 */
class WalasStudentController extends WalasApiController
{
    /**
     * GET /api/v1/walas/students
     * Get all students with optional filters
     * Query: ?class_id=X&year_ajaran=2025-2026&status=Aktif&page=1&limit=20
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'class_id' => 'nullable|integer',
                'year_ajaran' => 'nullable|string',
                'status' => 'nullable|in:Aktif,Nonaktif',
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 20;

            $query = Siswa::query();

            // Filter by class
            if (!empty($validated['class_id'])) {
                $query->where('id_rombel', $validated['class_id']);
            }

            // Filter by year
            if (!empty($validated['year_ajaran'])) {
                $query->where('tahun_ajaran', $validated['year_ajaran']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            $total = $query->count();
            
            $students = $query
                ->with(['bioSiswa', 'rombel'])
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'nisn' => $student->nisn,
                    'nama' => $student->nama,
                    'class_id' => $student->id_rombel,
                    'nama_kelas' => $student->rombel->nama_rombel ?? null,
                    'jurusan' => $student->rombel->jurusan->nama_jurusan ?? null,
                    'status' => $student->status,
                    'tgl_lahir' => $student->bioSiswa->tgl_lahir ?? null,
                    'alamat' => $student->bioSiswa->alamat ?? null,
                    'parent_name' => $student->bioSiswa->nama_orang_tua ?? null,
                    'parent_phone' => $student->bioSiswa->no_hp_orang_tua ?? null
                ];
            });

            return $this->successResponse(
                $this->paginatedResponse($formattedStudents, $page, $limit, $total),
                'Students retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve students: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/students/{id}
     * Get single student detail
     */
    public function show($id)
    {
        try {
            $student = Siswa::with(['bioSiswa', 'rombel', 'rombel.jurusan'])
                ->findOrFail($id);

            $data = [
                'id' => $student->id,
                'nisn' => $student->nisn,
                'nama' => $student->nama,
                'class_id' => $student->id_rombel,
                'nama_kelas' => $student->rombel->nama_rombel ?? null,
                'jurusan' => $student->rombel->jurusan->nama_jurusan ?? null,
                'status' => $student->status,
                'tgl_lahir' => $student->bioSiswa->tgl_lahir ?? null,
                'alamat' => $student->bioSiswa->alamat ?? null,
                'parent_name' => $student->bioSiswa->nama_orang_tua ?? null,
                'parent_phone' => $student->bioSiswa->no_hp_orang_tua ?? null,
                'gender' => $student->bioSiswa->jenis_kelamin ?? null,
                'agama' => $student->bioSiswa->agama ?? null,
                'no_telpon' => $student->bioSiswa->no_telpon ?? null,
                'email' => $student->bioSiswa->email ?? null
            ];

            return $this->successResponse($data, 'Student detail retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Student not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/students/by-class/{classId}
     * Get all students in a class
     */
    public function byClass($classId)
    {
        try {
            $rombel = Rombel::findOrFail($classId);
            
            $students = Siswa::where('id_rombel', $classId)
                ->where('status', 'Aktif')
                ->with(['bioSiswa', 'rombel'])
                ->get();

            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'nisn' => $student->nisn,
                    'nama' => $student->nama,
                    'class_id' => $student->id_rombel,
                    'nama_kelas' => $student->rombel->nama_rombel ?? null,
                    'status' => $student->status
                ];
            });

            return $this->successResponse($formattedStudents, 'Class students retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Class not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve class students: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/walas/students/search
     * Search students by name
     * Query: ?query=nama_siswa&limit=10
     */
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'query' => 'required|string|min:2',
                'limit' => 'nullable|integer|min:1|max:50'
            ]);

            $limit = $validated['limit'] ?? 10;
            $query = $validated['query'];

            $students = Siswa::where('nama', 'LIKE', '%' . $query . '%')
                ->orWhere('nisn', 'LIKE', '%' . $query . '%')
                ->where('status', 'Aktif')
                ->with(['bioSiswa', 'rombel'])
                ->limit($limit)
                ->get();

            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'nisn' => $student->nisn,
                    'nama' => $student->nama,
                    'class_id' => $student->id_rombel,
                    'nama_kelas' => $student->rombel->nama_rombel ?? null
                ];
            });

            return $this->successResponse($formattedStudents, 'Search results retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Search failed: ' . $e->getMessage(), 500);
        }
    }
}
