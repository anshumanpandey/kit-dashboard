<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToSubitemBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subitem_barcodes', function (Blueprint $table) {
            $table->enum('status', ['available', 'awaitingbooking', 'onjob', 'intransit'])->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subitem_barcodes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
