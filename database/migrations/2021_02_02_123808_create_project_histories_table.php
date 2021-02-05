<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_histories', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->id();
			
			$table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			
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
        Schema::dropIfExists('project_histories');
    }
}
