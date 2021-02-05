<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('created_by');
			$table->integer('updated_by');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

			$table->integer('created_by');
			$table->integer('updated_by');
        
        });

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'updated_by') && Schema::hasColumn('users', 'created_by'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('organisation_id');
                $table->dropColumn('updated_by');
                $table->dropColumn('created_by');
            });
        }
        if (Schema::hasColumn('organizations', 'updated_by') && Schema::hasColumn('organizations', 'created_by'))
        {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('updated_by');
                $table->dropColumn('created_by');
            });
        }
    }
}
