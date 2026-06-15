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
    Schema::create(
        'school_settings',
        function (Blueprint $table) {

            $table->id();

            $table->string('school_name');

            $table->enum(
                'education_level',
                [
                    'SD',
                    'SMP',
                    'SMA',
                    'SMK'
                ]
            );

            $table->string('phone')
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->string('academic_year')
                ->nullable();

            $table->string('logo')
                ->nullable();

            $table->timestamps();
        }
    );
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
