<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::createIfNotExists('biodata_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walas_id');
            $table->foreign('walas_id')->references('id')->on('walas')->onDelete('cascade')->onUpdate ('cascade');
            $table->unsignedBigInteger('siswas_id');
            $table->foreign('siswas_id')->references('id')->on('siswas')->onDelete('cascade')->onUpdate ('cascade');
            $table->string('nama_lengkap',255);
            $table->enum('jenis_kelamin', ['Perempuan', 'Laki-laki'])->default('Laki-laki');
            $table->string('tempat_lahir',255);
            $table->date('tanggal_lahir',255);
            $table->text('alamat');
            $table->text('alamat_maps');
            $table->string('fotorumah_url',255)->nullable();
            $table->enum('kepemilikan_rumah', ['Lunas', 'Sewa', 'KPR/Kredit', 'Kontrak', 'Inden'])->default('Lunas');
            $table->enum('jalur_masuk', ['Afirmasi', 'Zonasi', 'Rapor', 'Prestasi', 'Anak Guru','Perpindahan Orang Tua',])->default('Rapor');
            $table->string('jarak_rumah',255);
            $table->string('transportasi_sekolah',255);
            $table->string('transportasi_rumah',255);
            $table->enum('agama', ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Budha','Konghucu',])->default('Islam');
            $table->string('kewarganegaraan',20);
            $table->string('anak_ke',5);
            $table->string('jumlah_saudara', 5);
            $table->string('no_wa',14);
            $table->string('email', 50);
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique();
            $table->string('kelas', 4);
            $table->enum('kompetensi', ['SIJA', 'TKJ', 'RPL', 'DKV', 'DPIB', 'TKP', 'TP','TFLM', 'TKR', 'TOI'])->default('SIJA');
            $table->string('tahun_masuk', 4);
            $table->string('nama_ayah', 80);
            $table->string('pekerjaan_ayah', 80);
            $table->string('tempat_lahir_ayah',50);
            $table->date('tanggal_lahir_ayah',50);
            $table->text('alamat_ayah');
            $table->string('no_wa_ayah',15);
            $table->string('nama_ibu', 50);
            $table->string('pekerjaan_ibu', 50);
            $table->string('tempat_lahir_ibu',50);
            $table->date('tanggal_lahir_ibu',50);
            $table->text('alamat_ibu');
            $table->string('no_wa_ibu',15);
            $table->string('pendapatan_ortu', 80);
            $table->string('namasekolah_asal',50);
            $table->text('alamat_sekolah');
            $table->string('tahun_lulus',4);
            $table->string('riwayat_penyakit',50);
            $table->string('alergi',50);
            $table->text('prestasi_akademik');
            $table->text('prestasi_non_akademik');
            $table->text('pengalaman_ekskul');
            $table->text('kepribadian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_siswas');
    }
};



