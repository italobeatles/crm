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
        Schema::create('tbnotes', function (Blueprint $table) {
            $table->id();
            $table->string('relacionado_tipo', 30)->index();
            $table->unsignedBigInteger('relacionado_id')->index();
            $table->text('conteudo');
            $table->foreignId('id_usuario')->constrained('tbusers');
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
        Schema::dropIfExists('tbnotes');
    }
};
