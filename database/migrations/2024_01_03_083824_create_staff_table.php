<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matrix\Operators\Division;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Division::class,'division_id')->constrained()->cascadeOnDelete();
            $table->longText('foto')->nullable();
            $table->string('nip')->unique();
            $table->string('email')->unique();
            $table->string('nama');
            $table->integer('surat_sp')->default(0);
            $table->integer('jatah_cuti')->default(0);
            $table->string('status');
            $table->string('no_hp');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_ktp')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('gender');
            $table->string('jabatan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
