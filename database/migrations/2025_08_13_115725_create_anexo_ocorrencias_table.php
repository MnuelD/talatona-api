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
        Schema::create('anexo_ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ocorrencia_id')->nullable(false);
            $table->foreign('ocorrencia_id')->references('id')->on('ocorrencias'); // Referenciando a tabela correta
            $table->string('anexo');
            $table->string('descricao')->nullable(); // Descrição opcional do anexo
            $table->string('meta_keywords')->nullable(); // Meta keywords opcionais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexo_ocorrencias');
    }
};
