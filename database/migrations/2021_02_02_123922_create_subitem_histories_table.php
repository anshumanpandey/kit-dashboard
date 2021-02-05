<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubitemHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subitem_histories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
			
			$table->unsignedBigInteger('sub_item_id');
            $table->foreign('sub_item_id')->references('id')->on('subitems')->onDelete('cascade');
			$table->string('notificationtext');
			
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
        Schema::dropIfExists('subitem_histories');
    }
}
