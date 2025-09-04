<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festa_id')->constrained('festas')->onDelete('cascade');
            $table->string('codigo', 20)->unique();
            $table->json('numeros');
            $table->string('hash_integridade', 64)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartelas');
    }
};
