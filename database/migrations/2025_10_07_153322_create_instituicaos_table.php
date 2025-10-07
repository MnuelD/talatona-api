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
        Schema::create('instituicaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('slug')->unique();
            $table->string('ponto_referencia')->nullable();
            $table->string('imagem')->nullable();
            $table->text('descricao')->nullable();
            $table->string('telefone'); // Responsável pela instituição (usuário)
            $table->unsignedBigInteger('responsavel'); // Responsável pela instituição (usuário)
            $table->unsignedBigInteger('localizacao');// Localização (bairro ou zona)
            $table->enum('categoria', ['hospital', 'escola', 'esquadra', 'empresa', 'mercado', 'instituicao'])->default('instituicao');
            $table->enum('tipo_instituicao', ['publica', 'privada'])->default('publica');
            $table->foreign('responsavel')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('localizacao')->references('id')->on('bairros')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicaos');
    }
};
