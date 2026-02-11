<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Walas;

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        $walasId = session('walas_id');
        $walas = Walas::find($walasId);

        // Mapping nama walas 
        $kelasMap = [
            'Mono Sujono' => ['XII SIJA 1'],
            'Doni' => ['XI DKV 1'],
            'Rahma Donawati' => ['XI DKV 2 1'],

        ];

        $kelasBoleh = [];
        if ($walas && isset($kelasMap[$walas->nama])) {
            $kelasBoleh = $kelasMap[$walas->nama];
        }

        $url = config('services.absensi_yosua.url');
        $token = config('services.absensi_yosua.token');

        $response = Http::withHeaders([
            'X-API-KEY' => $token
        ])->get($url . '/data-absen');

        $allData = $response->successful() ? $response->json() : [];

        // Filter data
        $dataAbsensi = collect($allData)->filter(function ($item) use ($kelasBoleh) {
            return in_array($item['class_name'], $kelasBoleh);
        });


        $namaKelas = $kelasBoleh[0] ?? 'Tidak Diketahui';

        return view('homepagegtk.absensi', compact('dataAbsensi', 'namaKelas'));
    }
}