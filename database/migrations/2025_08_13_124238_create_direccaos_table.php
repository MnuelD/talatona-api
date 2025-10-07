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
        Schema::create('direccaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('responsavel_id')->nullable(false);
            $table->foreign('responsavel_id')->references('id')->on('users'); // Referenciando a tabela de usuários
            $table->string('telefone')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('imagem')->nullable(); // Imagem da direção, se aplicável
            $table->string('slug')->nullable()->unique(); // Slug para URLs amigáveis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccaos');
    }
};
