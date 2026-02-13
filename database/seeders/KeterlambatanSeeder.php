<?php

namespace Database\Seeders;

use App\Models\Keterlambatan;
use App\Models\Siswa;
use App\Models\Walas;
use Illuminate\Database\Seeder;

class KeterlambatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table already has data
        if (Keterlambatan::count() > 0) {
            $this->command->info('Keterlambatan table already has data. Skipping seeder.');
            return;
        }

        $this->command->info('Seeding Keterlambatan data...');

        // Get some walas and siswa to use for foreign keys
        $walas = Walas::all();
        $siswas = Siswa::all();

        if ($walas->isEmpty() || $siswas->isEmpty()) {
            $this->command->warn('No Walas or Siswa found. Please seed those tables first.');
            return;
        }

        // Dummy keterlambatan data
        $dummyData = [
            // Siswa 1: Multiple tardiness records
            [
                'walas_id' => $walas->first()->id,
                'siswas_id' => $siswas->first()->id,
                'kelas' => 'X SIJA 1',
                'tanggal' => now()->subDays(10)->format('Y-m-d'),
                'jam_masuk' => '07:15:00',
                'menit_terlambat' => 15,
                'alasan' => 'Kemacetan jalanan',
                'keterangan' => 'Tercatat oleh walas',
            ],
            [
                'walas_id' => $walas->first()->id,
                'siswas_id' => $siswas->first()->id,
                'kelas' => 'X SIJA 1',
                'tanggal' => now()->subDays(8)->format('Y-m-d'),
                'jam_masuk' => '07:20:00',
                'menit_terlambat' => 20,
                'alasan' => 'Bangun terlambat',
                'keterangan' => 'Alasan diterima',
            ],
            [
                'walas_id' => $walas->first()->id,
                'siswas_id' => $siswas->first()->id,
                'kelas' => 'X SIJA 1',
                'tanggal' => now()->subDays(6)->format('Y-m-d'),
                'jam_masuk' => '07:10:00',
                'menit_terlambat' => 10,
                'alasan' => 'Motor mogok',
                'keterangan' => 'Dimaafkan',
            ],

            // Siswa 2: Single tardiness
            [
                'walas_id' => $walas->first()->id,
                'siswas_id' => $siswas->skip(1)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X TKJ 1',
                'tanggal' => now()->subDays(9)->format('Y-m-d'),
                'jam_masuk' => '07:25:00',
                'menit_terlambat' => 25,
                'alasan' => 'Antar adik ke sekolah',
                'keterangan' => 'Tercatat oleh walas',
            ],

            // Siswa 3: Multiple tardiness
            [
                'walas_id' => $walas->skip(1)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(2)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X SIJA 2',
                'tanggal' => now()->subDays(7)->format('Y-m-d'),
                'jam_masuk' => '07:30:00',
                'menit_terlambat' => 30,
                'alasan' => 'Sarapan pagi',
                'keterangan' => 'Menunggu hasil banding',
            ],
            [
                'walas_id' => $walas->skip(1)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(2)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X SIJA 2',
                'tanggal' => now()->subDays(5)->format('Y-m-d'),
                'jam_masuk' => '07:15:00',
                'menit_terlambat' => 15,
                'alasan' => 'Macet di jalan',
                'keterangan' => 'Dimaafkan',
            ],

            // Siswa 4: Heavy tardiness
            [
                'walas_id' => $walas->skip(1)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(3)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X TKJ 2',
                'tanggal' => now()->subDays(4)->format('Y-m-d'),
                'jam_masuk' => '07:45:00',
                'menit_terlambat' => 45,
                'alasan' => 'Lupa jam waktunya',
                'keterangan' => 'Catatan untuk orang tua',
            ],

            // Siswa 5: Multiple tardiness
            [
                'walas_id' => $walas->skip(2)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(4)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X SIJA 1',
                'tanggal' => now()->subDays(3)->format('Y-m-d'),
                'jam_masuk' => '07:20:00',
                'menit_terlambat' => 20,
                'alasan' => 'Antrian mandi pagi',
                'keterangan' => null,
            ],
            [
                'walas_id' => $walas->skip(2)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(4)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X SIJA 1',
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'jam_masuk' => '07:12:00',
                'menit_terlambat' => 12,
                'alasan' => 'Hujan lebat',
                'keterangan' => 'Dimaafkan karena cuaca',
            ],

            // Siswa 6: One record
            [
                'walas_id' => $walas->skip(2)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(5)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X TKJ 1',
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'jam_masuk' => '07:35:00',
                'menit_terlambat' => 35,
                'alasan' => 'Ada keperluan PPK',
                'keterangan' => 'Sudah dikonfirmasi',
            ],

            // More students for variety
            [
                'walas_id' => $walas->skip(3)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(6)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X SIJA 2',
                'tanggal' => now()->format('Y-m-d'),
                'jam_masuk' => '07:18:00',
                'menit_terlambat' => 18,
                'alasan' => 'Ketinggalan buku',
                'keterangan' => null,
            ],
            [
                'walas_id' => $walas->skip(3)->first()?->id ?? $walas->first()->id,
                'siswas_id' => $siswas->skip(7)->first()?->id ?? $siswas->first()->id,
                'kelas' => 'X TKJ 2',
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'jam_masuk' => '07:22:00',
                'menit_terlambat' => 22,
                'alasan' => 'Bantu ibu jualan',
                'keterangan' => 'Banding diterima',
            ],
        ];

        // Insert data
        foreach ($dummyData as $data) {
            // Skip if student or walas doesn't exist anymore
            if (!Siswa::find($data['siswas_id']) || !Walas::find($data['walas_id'])) {
                continue;
            }
            Keterlambatan::create($data);
        }

        $this->command->info('✅ Keterlambatan seeder completed successfully!');
    }
}
