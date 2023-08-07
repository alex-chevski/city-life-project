<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{

    public function up()
    {
        Schema::create('advert_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            NestedSet::columns($table);
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_categories');
    }
};
