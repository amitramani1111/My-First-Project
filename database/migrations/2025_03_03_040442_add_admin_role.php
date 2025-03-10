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
        Schema::table('admins', function ($table) {
            $table->enum('role', ['admin', 'reader'])->default('reader')->after('id');
            $table->enum('status', ['online', 'offline'])->default('offline')->after('name');
            $table->string('profile')->after('otp');
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('status');
            $table->dropColumn('profile');
            $table->dropColumn('deleted_at');
        });
    }
};
