<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBarcodeFromSubitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subitems', function (Blueprint $table) {
            $table->dropColumn('barcode_no');
            $table->dropColumn('barcode_url');
            $table->dropColumn('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subitems', function (Blueprint $table) {
            $table->string('barcode_no', 191)->nullable()->after('quantity');
            $table->string('barcode_url', 191)->nullable()->after('barcode_no');
            $table->string('code', 191)->nullable()->after('barcode_url');
        });
    }
}
