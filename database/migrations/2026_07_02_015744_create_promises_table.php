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
        Schema::create('promises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Se for coordenador ou a pessoa que vai fazer a doação estiver logada, confirma automaticamente
            $table->string('donor_name'); // Obrigatório se a pessoa não estiver logada
            $table->string('donor_whatsapp')->nullable(); // Obrigatório se a pessoa não estiver logada, para enviar código de confirmação
            $table->string('confirmation_code', 6)->nullable(); // Código para a pessoa confirmar a promessa, caso não esteja logada
            $table->datetime('confirmed_at')->nullable()->index(); // Se a pessoa estiver logada, já confirma automaticamente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promises');
    }
};
