<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();
			
			$table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			
			$table->string('sub_item_barcode', 50);
			
			$table->enum('checkout_status_id', ['0', '1']);
			
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
        Schema::dropIfExists('checkouts');
    }
}
