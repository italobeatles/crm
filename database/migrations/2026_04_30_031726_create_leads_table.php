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
        Schema::create('tbleads', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->string('email')->nullable()->index();
            $table->string('telefone', 20)->nullable()->index();
            $table->string('origem', 30)->default('outro')->index();
            $table->string('status', 30)->default('novo')->index();
            $table->text('observacoes')->nullable();
            $table->foreignId('id_usuario_responsavel')->constrained('tbusers');
            $table->unsignedBigInteger('id_cliente_convertido')->nullable()->index();
            $table->dateTime('convertido_em')->nullable();
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
        Schema::dropIfExists('tbleads');
    }
};
