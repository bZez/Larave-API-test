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
        Schema::create('cdrs', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('ref', 36)->index();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->integer('total_energy')->nullable();
            $table->integer('total_cost')->unsigned()->nullable();
            $table->unsignedInteger('evse_id');
            $table->foreign('evse_id')
                ->references('id')
                ->on('evses')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdrs');
    }
};
