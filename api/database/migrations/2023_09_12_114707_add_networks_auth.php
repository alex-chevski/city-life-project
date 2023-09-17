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
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });

        Schema::create('user_networks', static function (Blueprint $table): void {
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('network');
            $table->string('identity');
            $table->primary(['user_id', 'identity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_networks');

        Schema::table('users', static function (Blueprint $table): void {
            $table->string('email')->change();
            $table->string('password')->change();
        });
    }
};
