<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evses', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('ref', 36)->index();
            $table->string('address', 45);
            $table->unsignedInteger('operator_id');
            $table->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evses');
    }
};
