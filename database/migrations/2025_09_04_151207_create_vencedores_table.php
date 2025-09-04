<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vencedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festa_id')->constrained('festas')->onDelete('cascade');
            $table->foreignId('cartela_id')->constrained('cartelas')->onDelete('cascade');
            $table->foreignId('premio_id')->constrained('premios')->onDelete('cascade');
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->enum('status', ['pendente', 'confirmado', 'invalido'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vencedores');
    }
};
