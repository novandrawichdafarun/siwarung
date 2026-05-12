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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warung_id')->constrained('warung')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('harga_jual');
            $table->unsignedBigInteger('harga_beli')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('stok_minimal')->default(5);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
