<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbcontacts', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('tbcustomers')->cascadeOnDelete();
        });

        Schema::table('tbleads', function (Blueprint $table) {
            $table->foreign('id_cliente_convertido')->references('id')->on('tbcustomers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tbcontacts', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
        });

        Schema::table('tbleads', function (Blueprint $table) {
            $table->dropForeign(['id_cliente_convertido']);
        });
    }
};
