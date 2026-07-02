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
        Schema::create('promise_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promise_id')->nullable()->constrained()->nullOnDelete(); // O próprio criador da campanha pode inserir promessas doação de modo avulso
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('status', 20)->default('pending'); // pending, confirmed, delivered, canceled | Muda para confirmed automaticamente com o confirmed de promises
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promise_items');
    }
};
