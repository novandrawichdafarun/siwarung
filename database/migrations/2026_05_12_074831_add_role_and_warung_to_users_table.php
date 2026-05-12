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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'kasir', 'super_admin'])->default('owner')->after('password');
            $table->foreignId('warung_id')->nullable()->constrained('warung')->after('role')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['warung_id']);
            $table->dropColumn('warung_id');
            $table->dropColumn('role');
        });
    }
};
