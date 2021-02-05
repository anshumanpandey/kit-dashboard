<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkeditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkeditems', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();
			
			$table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			
			$table->unsignedBigInteger('linked_item_id');
            $table->foreign('linked_item_id')->references('id')->on('items')->onDelete('cascade');
			
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
        Schema::dropIfExists('linkeditems');
    }
}
