<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubitemBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subitem_barcodes', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('sub_item_id');
            $table->foreign('sub_item_id')->references('id')->on('subitems')->onDelete('cascade');
			
			$table->string('barcode_no', 191)->nullable();
            $table->string('barcode_url', 191)->nullable();
			
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
        Schema::dropIfExists('subitem_barcodes');
    }
}
