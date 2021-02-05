<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();

            $table->string('title');
            $table->string('status');
            $table->date('pickup_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('expected_return_date')->nullable();

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
        Schema::dropIfExists('projects');
    }
}
