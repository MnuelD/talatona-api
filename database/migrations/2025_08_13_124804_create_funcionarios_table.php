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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users'); // Referenciando a tabela de usuários
            $table->unsignedBigInteger('direccao_id')->nullable(false);
            $table->foreign('direccao_id')->references('id')->on('direccaos'); // Referenciando a tabela de usuários
            $table->string('slug')->nullable()->unique(); // Slug para URLs amigáveis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
