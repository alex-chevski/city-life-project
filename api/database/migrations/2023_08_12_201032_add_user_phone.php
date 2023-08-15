<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('phone_verified')->default(false)->after('phone');
            $table->string('phone_verify_token')->nullable()->after('verify_token');
            $table->timestamp('phone_verify_token_expire')->nullable()->after('phone_verify_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('phone_verify_token_expire');
            $table->dropColumn('phone_verify_token');
            $table->dropColumn('phone_verified');
            $table->dropColumn('phone');
        });
    }
};
