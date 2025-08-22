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
        Schema::create('bairros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comuna_id')->nullable(false);
            $table->foreign('comuna_id')->references('id')->on('comunas'); // Referenciando a tabela correta
            $table->string('nome')->nullable(false)->unique(); // Campo obrigatório, já é nullable(false) por padrão
            $table->string('slug')->nullable(false)->unique(); // Slug para URLs amigáveis
            $table->string('ponto_referencia')->nullable();
            $table->string('imagem')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bairros');
    }
};
