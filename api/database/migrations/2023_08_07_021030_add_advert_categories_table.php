<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('advert_categories', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            NestedSet::columns($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_categories');
    }
};
