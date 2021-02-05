<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_barcodes', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('sub_item_id');
            $table->foreign('sub_item_id')->references('id')->on('subitems')->onDelete('cascade');
			
			$table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			
			$table->unsignedBigInteger('barcode_id');
            $table->foreign('barcode_id')->references('id')->on('subitem_barcodes')->onDelete('cascade');
			
			$table->integer('created_by');
			$table->integer('updated_by');
			
			$table->string('barcode', 191);
			$table->enum('status', ['checkin', 'checkout'])->default('checkout');
			
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
        Schema::dropIfExists('project_barcodes');
    }
}
