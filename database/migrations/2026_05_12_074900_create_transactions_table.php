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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warung_id')->constrained('warung')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('total_gross');
            $table->decimal('commission_rate', 5, 4)->default(0.0050);
            $table->unsignedBigInteger('commission_amount');
            $table->unsignedBigInteger('total_net');
            $table->enum('payment_method', ['cash', 'qris']);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->text('midtrans_snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
