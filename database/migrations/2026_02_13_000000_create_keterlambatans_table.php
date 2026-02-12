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
        Schema::create('keterlambatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walas_id');
            $table->foreign('walas_id')->references('id')->on('walas')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('siswas_id');
            $table->foreign('siswas_id')->references('id')->on('siswas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('kelas')->index();
            $table->date('tanggal')->index();
            $table->time('jam_masuk');
            $table->integer('menit_terlambat');
            $table->string('alasan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index for common queries
            $table->index(['walas_id', 'tanggal']);
            $table->index(['siswas_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterlambatans');
    }
};
