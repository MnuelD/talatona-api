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
        Schema::create('noticias', function (Blueprint $table) {
             $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->unsignedBigInteger('categoria_id')->nullable(false);
            $table->foreign('categoria_id')->references('id')->on('categorias'); // Referenciando a tabela correta

            $table->string('link')->nullable();
            $table->string('imagem')->nullable();
            $table->enum('status', ['on', 'off'])->default('on');
            $table->string('fonte')->nullable();
            $table->string('slug')->unique();
            // SEO
            $table->string('meta_titulo')->nullable();
            $table->text('meta_descricao')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_imagem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
