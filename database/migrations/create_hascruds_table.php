<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class create_hascruds_table
 * @author sheenazien8
 */
class CreateHascrudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hascruds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('show_plural_name');
            $table->string('show_singular_name');
            $table->string('controller');
            $table->string('request');
            $table->boolean('permission')->default(false);
            $table->boolean('server_side')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hascruds');
    }
}
