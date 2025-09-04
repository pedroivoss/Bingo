<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festa_id')->constrained('festas')->onDelete('cascade');
            $table->integer('numero');
            $table->char('letra', 1);
            $table->integer('ordem');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteios');
    }
};
