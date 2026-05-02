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
        Schema::create('tbatividades', function (Blueprint $table) {
            $table->id();
            $table->string('relacionado_tipo', 30)->nullable()->index();
            $table->unsignedBigInteger('relacionado_id')->nullable()->index();
            $table->string('tipo', 30)->index();
            $table->string('titulo', 150);
            $table->text('descricao')->nullable();
            $table->dateTime('data_vencimento')->nullable()->index();
            $table->dateTime('concluido_em')->nullable()->index();
            $table->string('status', 20)->default('pendente')->index();
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
        Schema::dropIfExists('tbatividades');
    }
};
