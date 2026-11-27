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
        Schema::table('classrooms', function (Blueprint $table) {
            // Drop existing foreign key before making major_id nullable
            $table->dropForeign(['major_id']);

            $table->unsignedBigInteger('major_id')->nullable()->change();
            $table->string('class_number', 10)->change();

            // Re-attach foreign key; set to null if a major is deleted
            $table->foreign('major_id')
                ->references('id')
                ->on('majors')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['major_id']);

            // Note: ensure no null major_id or non-integer class_number rows exist before rolling back
            $table->unsignedBigInteger('major_id')->nullable(false)->change();
            $table->integer('class_number')->change();

            $table->foreign('major_id')
                ->references('id')
                ->on('majors')
                ->cascadeOnDelete();
        });
    }
};
