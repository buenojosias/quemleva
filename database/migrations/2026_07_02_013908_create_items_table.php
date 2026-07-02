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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Nome do item necessário
            $table->string('unit', 3); // Unidade de medida (ex: kg, un, l) - virá de class Enum
            $table->integer('quantity'); // Quantidade necessária do item
            $table->date('delivery_date')->nullable(); // Data limite para entrega do item específico
            $table->mediumText('note')->nullable(); // Observações adicionais sobre o item
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
