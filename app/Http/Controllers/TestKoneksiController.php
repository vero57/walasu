<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TestKoneksiController extends Controller
{
    public function kirimData()
    {
        $url   = config('services.absensi_yosua.url');
        $token = config('services.absensi_yosua.token');

        $response = Http::withHeaders([
            'X-API-KEY' => $token
        ])
        ->acceptJson()
        ->get($url . '/users');

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'status'  => 'Gagal',
            'pesan'   => $response->json()['message'] ?? $response->reason(), 
            'code'    => $response->status()
        ], $response->status());
    }
}