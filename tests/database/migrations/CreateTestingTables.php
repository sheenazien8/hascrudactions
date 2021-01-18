<?php

namespace Sheenazien8\Hascrudactions\Tests\database\migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTestingTables
 * @author sheenazien8
 */
class CreateTestingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testing_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('join_date');
            $table->double('salary');
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
        Schema::dropIfExists('testing_tables');
    }
}
