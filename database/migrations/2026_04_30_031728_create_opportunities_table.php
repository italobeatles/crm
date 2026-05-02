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
        Schema::create('tboportunidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('tbcustomers');
            $table->foreignId('id_lead')->nullable()->constrained('tbleads');
            $table->string('titulo', 150);
            $table->decimal('valor', 14, 2)->default(0);
            $table->string('etapa', 30)->default('prospeccao')->index();
            $table->unsignedTinyInteger('probabilidade')->default(10);
            $table->date('data_prevista_fechamento')->nullable()->index();
            $table->string('status', 20)->default('aberta')->index();
            $table->text('observacoes')->nullable();
            $table->foreignId('id_usuario_responsavel')->constrained('tbusers');
            $table->dateTime('ganho_em')->nullable();
            $table->dateTime('perdido_em')->nullable();
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
        Schema::dropIfExists('tboportunidades');
    }
};
