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
        Schema::create('paginas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('imagem')->nullable();

            // Controle de visualizações
            $table->timestamp('ultima_visualizacao')->nullable();
            $table->unsignedBigInteger('visualizacoes')->default(0);

            // SEO
            $table->text('meta_keywords')->nullable();


            // Status
            $table->enum('estado', ['ativo', 'inativo', 'rascunho'])->default('ativo');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paginas');
    }
};
