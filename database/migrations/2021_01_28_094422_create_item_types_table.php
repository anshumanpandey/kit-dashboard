<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
		
		///insert item_types
		DB::table('item_types')->insert([
			'name' => 'Cable',
			'created_by' => '1',
			'updated_by' => '1',
		]);
		
		DB::table('item_types')->insert([
			'name' => 'Camera',
			'created_by' => '1',
			'updated_by' => '1',
		]);
		
		DB::table('item_types')->insert([
			'name' => 'Misc',
			'created_by' => '1',
			'updated_by' => '1',
		]);
		
		DB::table('item_types')->insert([
			'name' => 'Converter',
			'created_by' => '1',
			'updated_by' => '1',
		]);
		
		DB::table('item_types')->insert([
			'name' => 'Computer',
			'created_by' => '1',
			'updated_by' => '1',
		]);
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_types');
    }
}
