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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('refund_amount', 12, 2)->nullable()->after('cash_payment_proof');
            $table->string('refund_status')->default('none')->after('cash_payment_proof');
            // values: 'none', 'pending_parent_response', 'info_received', 'completed'
            $table->text('refund_bank_details')->nullable()->after('cash_payment_proof'); // Stores bank name, account number, account name
            $table->timestamp('refunded_at')->nullable()->after('cash_payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('refund_amount');
            $table->dropColumn('refund_status');
            $table->dropColumn('refund_bank_details');
            $table->dropColumn('refunded_at');
        });
    }
};
