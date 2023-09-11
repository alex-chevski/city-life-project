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
        Schema::create('banner_banners', static function (Blueprint $table): void {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('advert_categories');
            $table->unsignedInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('name');
            $table->integer('views')->nullable();
            $table->integer('limit');
            $table->integer('clicks')->nullable();
            $table->integer('cost')->nullable();
            $table->string('url');
            $table->string('format');
            $table->string('file');
            $table->string('status', 16);

            $table->timestamps();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_banners');
    }
};
