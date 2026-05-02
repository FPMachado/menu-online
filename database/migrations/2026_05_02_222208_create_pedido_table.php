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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('endereco_id')->nullable()->constrained()->nullOnDelete();

            $table->string('pagamento');
            $table->decimal('total', 10, 2);

            $table->string('status')->default('pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
