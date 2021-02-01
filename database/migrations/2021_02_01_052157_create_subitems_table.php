<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subitems', function (Blueprint $table) {
            $table->id();
			
			$table->string('serialnumber', 191);
            $table->string('pictureurl', 191)->nullable();
            $table->date('date_of_purchase')->nullable();
            $table->date('warranty_expiry_period')->nullable();
            $table->integer('quantity');
            
            $table->string('receipt_url', 191)->nullable();
            $table->string('barcode_no', 191)->nullable();
            $table->string('barcode_url', 191)->nullable();
            $table->string('condition', 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->string('notes', 191)->nullable();
            $table->string('code', 191)->nullable();
			
		
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			
            $table->unsignedBigInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
			
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
        Schema::dropIfExists('subitems');
    }
}
