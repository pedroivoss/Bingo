<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('festas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('is_cartela_cheia')->default(false);
            $table->date('data');
            $table->string('marca_dagua_path')->nullable();
            $table->string('coringa_path')->nullable();
            $table->string('cabecalho_path')->nullable(); // <<< Adicionado
            $table->text('rodape_html')->nullable();
            $table->json('configs_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festas');
    }
};
