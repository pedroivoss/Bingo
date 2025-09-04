<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festa_id')->constrained('festas')->onDelete('cascade');
            $table->string('primeira_cartela_codigo', 20);
            $table->integer('quantidade_por_folha');
            $table->string('pdf_path');
            $table->integer('pagina_inicio')->nullable();
            $table->integer('pagina_fim')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folhas');
    }
};
