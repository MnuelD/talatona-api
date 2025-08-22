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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ocorrencia_id')->nullable(false);
            $table->foreign('ocorrencia_id')->references('id')->on('ocorrencias'); // Referenciando a tabela de usuários
            $table->unsignedBigInteger('direccao_id')->nullable(false);
            $table->foreign('direccao_id')->references('id')->on('direccaos'); // Referenciando a tabela de usuários
            $table->unsignedBigInteger('responsavel_id')->nullable(false);
            $table->foreign('responsavel_id')->references('id')->on('funcionarios'); // Referenciando a tabela de funcionários
            $table->enum('status', ['aberto', 'em_andamento', 'resolvido', 'fechado'])->default('aberto');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
