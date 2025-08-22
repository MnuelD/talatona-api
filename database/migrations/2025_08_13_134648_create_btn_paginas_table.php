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
        Schema::create('btn_paginas', function (Blueprint $table) {
            $table->id();
            $table->string('texto')->nullable();
            $table->string('link')->unique();
            $table->text('icone')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->unsignedBigInteger('pagina_id')->nullable();
            $table->foreign('pagina_id')->references('id')->on('paginas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('btn_paginas');
    }
};
