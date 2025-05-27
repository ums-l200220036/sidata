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
        Schema::create('data_sektoral', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')->constrained('wilayah')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained('indikator')->onDelete('cascade');
            $table->foreignId('dimensi_id')->nullable()->constrained('dimensi')->onDelete('set null');

            $table->decimal('nilai', 12, 2);
            $table->string('satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sektoral');
    }
};
