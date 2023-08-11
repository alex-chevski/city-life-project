<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('regions', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();
            $table->unique(['parent_id', 'slug']);
            $table->unique(['parent_id', 'name']);
        });

        Schema::table('regions', static function (Blueprint $table): void {
            $table->foreign('parent_id')->references('id')->on('regions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
