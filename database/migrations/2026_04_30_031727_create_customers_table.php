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
        Schema::create('tbcustomers', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 2)->default('pj');
            $table->string('nome', 150);
            $table->string('documento', 20)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('telefone', 20)->nullable();
            $table->string('status', 20)->default('ativo')->index();
            $table->text('observacoes')->nullable();
            $table->foreignId('id_usuario_responsavel')->constrained('tbusers');
            $table->dateTime('criado_em')->useCurrent();
            $table->dateTime('atualizado_em')->nullable()->useCurrentOnUpdate();
            $table->dateTime('deletado_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbcustomers');
    }
};
