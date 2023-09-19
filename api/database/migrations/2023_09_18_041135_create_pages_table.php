<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('title');
            $table->string('menu_title')->nullable();
            $table->string('slug');
            $table->mediumText('content');
            $table->text('description')->nullable();
            $table->timestamps();
            NestedSet::columns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', static function (Blueprint $table): void {
            Schema::dropIfExists('pages');
        });
    }
};
