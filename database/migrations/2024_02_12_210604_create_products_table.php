<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('produto', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_barras')->unique();
        $table->string('descricao');
        $table->decimal('preco', 10, 2);
        // Adicione outros campos conforme necessário
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
