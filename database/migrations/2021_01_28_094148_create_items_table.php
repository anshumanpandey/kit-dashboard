<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->string('cable_length');
            $table->string('make');
            $table->string('model');
            $table->string('status');
            
            $table->unsignedBigInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->integer('created_by');
			$table->integer('updated_by');

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
        Schema::dropIfExists('items');
    }
}
