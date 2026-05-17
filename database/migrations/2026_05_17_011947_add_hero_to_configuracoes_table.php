<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->string('slogan')->nullable()->after('tempo_entrega');
            $table->text('descricao')->nullable()->after('slogan');
            $table->string('imagem_hero')->nullable()->after('descricao');
        });
    }

    public function down(): void
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->dropColumn(['slogan', 'descricao', 'imagem_hero']);
        });
    }
};
