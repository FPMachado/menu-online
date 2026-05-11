<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_restaurante')->default('Meu Restaurante');
            $table->string('logo')->nullable();
            $table->string('chave_pix')->nullable();
            $table->string('email_notificacao')->nullable();
            $table->decimal('taxa_entrega', 10, 2)->default(0);
            $table->boolean('aberto')->default(true);
            $table->string('horario_abertura')->default('08:00');
            $table->string('horario_fechamento')->default('22:00');
            $table->string('tempo_entrega')->default('30-45 min');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};
