<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('files', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('entity_name')->index();
            $table->integer('entity_id')->unsigned()->index();
            $table->string('name')->index();
            $table->string('link');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }
}
