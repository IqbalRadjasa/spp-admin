<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_parent_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->string('relationship')->nullable(); // e.g. 'father', 'mother', 'guardian'
            $table->timestamps();

            $table->unique(['student_id', 'parent_id']);
        });

        $existingLinks = DB::table('students')
            ->whereNotNull('parent_id')
            ->get(['id', 'parent_id']);

        foreach ($existingLinks as $link) {
            DB::table('student_parent_relations')->insert([
                'student_id' => $link->id,
                'parent_id'  => $link->parent_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::dropIfExists('parent_student');
    }
};
