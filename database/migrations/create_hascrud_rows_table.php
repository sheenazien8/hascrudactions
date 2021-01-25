<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class create_hascrud_rows_table
 * @author sheenazien8
 */
class CreateHascrudRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hascrud_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('hascrud_id')->constrained();
            $table->string('type');
            $table->string('collumn');
            $table->string('display_name');
            $table->longText('store_validation')->nullable();
            $table->longText('update_validation')->nullable();
            $table->boolean('is_multiple')->default(false);
            $table->boolean('is_required')->default(false);
            $table->boolean('show_in_read')->default(false);
            $table->boolean('show_in_create')->default(false);
            $table->boolean('show_in_edit')->default(false);
            $table->boolean('show_in_detail')->default(false);
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
        Schema::dropIfExists('hascrud_rows');
    }
}
