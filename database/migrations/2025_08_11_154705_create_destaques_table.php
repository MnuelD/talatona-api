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
        Schema::create('destaques', function (Blueprint $table) {
            $table->id();
            $table->string('titulo'); // Campo obrigatório, já é nullable(false) por padrão
            $table->text('descricao')->nullable(); // Melhor para textos longos
            $table->string('icone')->nullable(); // Classe do ícone (FontAwesome, etc.)
            $table->string('link_text')->nullable(); // Texto exibido no botão ou link
            $table->string('link')->nullable(); // URL interna ou externa
            $table->unsignedBigInteger('pagina')->nullable(false);
            $table->foreign('pagina')->references('id')->on('paginas'); // Referenciando a tabela correta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destaques');
    }
};
