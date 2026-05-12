<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commission_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warung_id')->constrained('warung')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->unsignedBigInteger('gross_amount');
            $table->decimal('commission_rate', 5, 4)->default(0.0050);
            $table->unsignedBigInteger('commission_amount');
            $table->enum('status', ['settled'])->default('settled');
            $table->timestamp('settled_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_ledger');
    }
};
