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
        Schema::create('tbcontacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente')->index();
            $table->string('nome', 120);
            $table->string('cargo', 80)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('principal')->default(false);
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
        Schema::dropIfExists('tbcontacts');
    }
};
