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
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();

            // Se o usuário estiver logado (relaciona com users)
            $table->string('codigo_ocorrencia')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users'); // Referenciando a tabela correta


            // Caso não logado ou anônimo
            $table->string('anonimo')->default(false);
            $table->string('nome')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();

            // Relacionamentos
            $table->unsignedBigInteger('bairro_id')->nullable(false);
            $table->foreign('bairro_id')->references('id')->on('bairros'); // Referenciando a tabela correta
            $table->unsignedBigInteger('tipoOcorrencia_id')->nullable(false);
            $table->foreign('tipoOcorrencia_id')->references('id')->on('tipo_ocorrencias');
            // Detalhes
            $table->string('localizacao_especifica')->nullable();
            $table->text('descricao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
};
