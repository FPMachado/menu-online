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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            $table->string('apelido')->nullable(); // Casa, Trabalho

            $table->string('cep')->nullable();

            $table->string('rua');
            $table->string('numero');
            $table->string('bairro');

            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();

            $table->string('complemento')->nullable();
            $table->string('referencia')->nullable();

            $table->boolean('principal')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
